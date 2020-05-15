<?php
session_start();
include_once 'classes/Database.php';
include_once 'classes/User.php';
include_once 'classes/Auth.php';
include_once 'classes/Utils.php';


$utils = new Utils;
$database = new Database;
$database->dataExist();

$user = new Auth;

$username = isset($_SESSION['login_user']) ? $_SESSION['login_user'] : null;
$password = isset($_SESSION['login_password']) ? $_SESSION['login_password'] : null;


if (!(isset($_SESSION['last_action']))) {
  $_SESSION['last_action'] = time();
}

if (isset($_SESSION['login_user']) && $username != null) {
  $data = $user->getUserData($username);
}

if (!isset($_SESSION['current_ip'])) {
  $_SESSION['current_ip'] = $utils->sanitize($_SERVER['REMOTE_ADDR']);
}

if (!(isset($_SESSION['csrf']))) {
  $_SESSION['csrf'] = hash_hmac('sha256',  uniqid(rand(), true), session_id()  . $_SESSION["current_ip"]);
}

if (!isset($_SESSION['login_user']) || !isset($_SESSION['login_password']) || !isset($_SESSION["current_ip"])) {
  $utils->redirect("login.php");
}

$expireAfter = 60;
if (isset($_SESSION['last_action'])) {
  $secondsInactive = time() - $_SESSION['last_action'];
  $expireAfterSeconds = $expireAfter * 60;

  if ($secondsInactive >= $expireAfterSeconds) {
    $utils->redirect("logout.php");
  }
}

if ($username != $data->username || $password != $data->password) {
  if (isset($_GET['msg']) && $utils->sanitize($_GET['msg']) == "yes") {
    $utils->redirect("logout.php?msg=update");
  }
  $utils->redirect("logout.php");
}

if ($user->isTwoFAEnabled($username) == "on") {
    if (!isset($_SESSION['OTP']) || $_SESSION['OTP'] !== "OK") {
        $utils->redirect("logout.php");
    }
}
