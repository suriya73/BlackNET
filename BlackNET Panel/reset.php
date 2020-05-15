<?php
session_start();
include_once 'classes/Database.php';
include_once 'classes/User.php';
include_once 'classes/Mailer.php';
include_once 'classes/ResetPassword.php';
include_once 'classes/Utils.php';

$utils = new Utils;

$key = $utils->sanitize($_GET['key']);
$updatePassword = new ResetPassword;
if ($updatePassword->isExist($key) == "Key Exist") {
    $data = $updatePassword->getUserAssignToToken($key);
    $question = $updatePassword->isQuestionEnabled($data->username);
    $answered = isset($_GET['answered']) ? $utils->sanitize($_GET['answered']) : "false";
    if ($question != false) {
        if ($answered != "true") {
            $utils->redirect("question.php?username=$data->username&key=$key");
        }
    }
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $Password = $utils->sanitize($_POST['password']);
        $confirmPassword = $utils->sanitize($_POST['confirmPassword']);
        if ($Password == $confirmPassword) {
            $msg = $updatePassword->updatePassword($key, $data->username, $_POST['password']);
        } else {
            $err = "Password confirm is incorrect";
        }
    }
} else {
    $utils->redirect("expire.php");
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - Reset Password</title>
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

          <h4>Reset Password</h4>
          <p>Please enter a strong password that contains 8 characters and at least one special character</p>
        </div>
        <form method="POST" action="">

          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="password" id="password" class="form-control" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least one number, one uppercase letter, lowercase letter, one character, and at least 8 or more characters" placeholder="New Password" required="required" autofocus="autofocus">
              <label for="password">New Password</label>
            </div>
          </div>

          <div class="form-group">
            <div class="form-label-group">
              <input type="password" name="confirmPassword" id="confirmPassword" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" title="Must contain at least one number, one uppercase letter, lowercase letter, one special character, and at least 8 or more characters" class="form-control" placeholder="Confirm Password" required="required" autofocus="autofocus">
              <label for="confirmPassword">Confirm Password</label>
            </div>
          </div>
          <button class="btn btn-primary btn-block" type="submit">Reset Password</button>

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