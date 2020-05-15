<?php
session_start();
include_once 'classes/Database.php';
include_once 'classes/User.php';
include_once 'classes/Auth.php';
include_once 'vendor/auth/FixedBitNotation.php';
include_once 'vendor/auth/GoogleAuthenticator.php';
include_once 'classes/Utils.php';

$_SESSION['OTP'] = "Waiting";

$utils = new Utils;

$auth = new Auth;

$uniqeid = hash("sha256", $utils->base64_encode_url($utils->sanitize($_SERVER['HTTP_USER_AGENT'])));

if (checkUniqeId($uniqeid) == true) {

    $_SESSION['OTP'] = "OK";

    $utils->redirect("index.php");
}

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST['AuthCode'];
    $secret = $auth->getSecret($_SESSION['login_user']);
    if ($g->checkCode($secret, $code)) {
        if (isset($_POST['remberme'])) {
            if (!isset($_COOKIE['2fa'])) {
                setcookie('2fa', 'true', time() + 2592000);
                setcookie('device_id', $uniqeid, time() + 2592000);
            }
        }

        $_SESSION['OTP'] = "OK";

        $utils->redirect("index.php");
    } else {
        $error = "Verification code is incorrect!!";
    }
}

function checkUniqeId($uniqeid)
{
    if (isset($_COOKIE['2fa'])) {
        if (isset($_COOKIE['device_id'])) {
            if ($_COOKIE['device_id'] == $uniqeid) {
                return true;
            } else {
                return false;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>

  <title>BlackNET - 2 Factor Authentication</title>

  <?php include_once 'components/css.php';?>
</head>

<body class="bg-dark">
  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form method="POST">
          <?php if (isset($error)): ?>
            <?php $utils->show_alert($error, "danger", "times-circle");?>
          <?php else: ?>
            <?php $utils->show_alert("Please open the app for the code.", "primary", "info-circle");?>
          <?php endif;?>
          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="AuthCode" pattern="[0-9]{6}" name="AuthCode" class="form-control" placeholder="Verification Code" required="required">
              <label for="AuthCode">Verification Code</label>
            </div>
          </div>
          <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="remberme" name="remberme">
            <label class="custom-control-label" for="remberme">Trust Device for 30 days</label>
          </div>
          <div class="pt-3">
            <button type="submit" class="btn btn-primary btn-block">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <?php include_once 'components/js.php';?>

</body>

</html>