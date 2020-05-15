<?php
include_once 'classes/Database.php';
include_once 'classes/User.php';
include_once 'classes/Mailer.php';
include_once 'classes/ResetPassword.php';
include_once 'classes/Utils.php';

$utils = new Utils;

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = isset($_POST['email']) ? $utils->sanitize($_POST['email']) : '';
    $resetPassword = new ResetPassword;
    if ($resetPassword->sendEmail($username)) {
        $msg = "Instructions has been send to your email";
    } else {
        $err = "Username does not exist!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - Forgot Password</title>
  <?php include_once 'components/css.php';?>

</head>

<body class="bg-dark">

  <div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Reset Password</div>
      <div class="card-body">
        <?php if (isset($msg)): ?>
          <?php $utils->show_alert($msg, "primary", "info-circle");?>
        <?php endif;?>
        <?php if (isset($err)): ?>
          <?php $utils->show_alert($err, "danger", "times-circle");?>
        <?php endif;?>
        <div class="text-center mb-4">
          <h4>Forgot your password?</h4>
          <p>Enter your email address and we will send you instructions on how to reset your password.</p>
        </div>
        <form method="POST">
          <div class="form-group">
            <div class="form-label-group">
              <input type="email" name="email" id="email" class="form-control" placeholder="Enter email address" required="required" autofocus="autofocus">
              <label for="email">Enter email address</label>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Reset Password</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="login.php">Login Page</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <?php include_once 'components/js.php';?>

</body>

</html>