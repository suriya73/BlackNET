<?php
session_start();
include_once 'classes/Database.php';
include_once 'classes/Settings.php';
include_once 'classes/User.php';
include_once 'classes/Auth.php';
include_once 'classes/Mailer.php';
include_once 'classes/Utils.php';

$utils = new Utils;
$settings = new Settings;
$auth = new Auth;

$lockout_time = 10;

$getSettings = $settings->getSettings(1);
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $utils->sanitize($_POST['username']);
    $password = $utils->sanitize($_POST['password']);

    $loginstatus = $auth->newLogin($username, hash("sha256", $auth->salt . $password));
    if ($loginstatus == "OK") {
        if (isset($_POST['g-recaptcha-response'])) {
            $response = $auth->recaptchaResponse($getSettings->recaptchaprivate, $_POST['g-recaptcha-response']);
            if (!$response->success) {
                $error = "Robot verification failed, please try again.";
            }
        }

        if (!isset($error)) {
            session_regenerate_id();
            $_SESSION['login_user'] = $username;
            $_SESSION['login_password'] = hash("sha256", $auth->salt . $password);
            if ($auth->isTwoFAEnabled($username) == "on") {
                $utils->redirect("auth.php");
            } else {
                $_SESSION['OTP'] = "OK";
                $utils->redirect("index.php");
            }
        }
    } elseif ($loginstatus == "Fail") {
        $error = "Username or Password is incorrect.";
    } elseif ($loginstatus == "Locked") {
        $error = "This account has been locked because of too many failed logins.\nIf this is the case, please try again in $lockout_time minutes.";
    } else {
        $error = "Unexpected error occurred !";
    }
}

$settings->dataExist();
?>

<!DOCTYPE html>
<html lang="en">
<?php include_once 'components/meta.php';?>
<title>BlackNET - Login</title>
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
          <?php endif;?>

          <?php if (isset($_GET['msg'])): ?>
            <?php $utils->show_alert("Profile has been updated, login again.", "success", "check-circle");?>
          <?php endif;?>

          <div class="form-group">
            <div class="form-label-group">
              <input type="text" id="username" class="form-control" name="username" placeholder="Username" required="required" autofocus="autofocus">
              <label for="username">Username</label>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label-group">
              <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="required">
              <label for="password">Password</label>
            </div>
          </div>
          <div class="align-content-center text-center">
            <?php if ($getSettings->recaptchastatus == "on"): ?>
              <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo $getSettings->recaptchapublic; ?>" required></div>
              </div>
            <?php endif;?>
          </div>
          <button type="submit" class="btn btn-primary btn-block">Login</button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="forgot-password.php">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
  <?php include_once 'components/js.php';?>
  <script src='https://www.google.com/recaptcha/api.js' async defer></script>

</body>

</html>