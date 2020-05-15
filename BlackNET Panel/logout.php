<?php
include_once 'classes/Utils.php';
$utils = new Utils;

session_start();
if (session_unset() && session_destroy()) {
    if (isset($_GET['msg'])) {
        $utils->redirect("login.php?msg=yes");
    } else {
        $utils->redirect("login.php");
    }
}
