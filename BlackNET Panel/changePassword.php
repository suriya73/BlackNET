<?php
include_once 'session.php';
include_once 'classes/Mailer.php';
include_once 'includes/questions.php';
//$current_username is in session.php
$user_question = $user->getQuestionByUser($data->username);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - User Settings</title>
  <?php include_once 'components/css.php';?>
  <link href="asset/css/bootstrap-switch.css" rel="stylesheet">
</head>

<body id="page-top">

  <?php include_once 'components/header.php';?>


  <div id="wrapper">
    <div id="content-wrapper">
      <div class="container-fluid">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">User Settings</a>
          </li>
        </ol>
        <div class="card mb-3">
          <div class="card-header">
            <i class="fas  fa-user-circle"></i>
            Update Password</div>
          <form method="POST" action="includes/updatePassword.php">
            <div class="card-body">
              <div class="container container-special">
                <?php if (isset($_GET['msg']) && $_GET['msg'] === "yes"): ?>
                  <?php $utils->show_alert("User settings has been updated", "success", "check-circle");?>
                <?php endif;?>

                <?php if (isset($_GET['msg']) && $_GET['msg'] === "csrf"): ?>
                  <?php $utils->show_alert("CSRF Token is invalid.", "danger", "times-circle");?>
                <?php endif;?>
              </div>
              <div class="container container-special">
                <div class="align-content-center justify-content-center">
                  <?php $utils->show_input("id", $data->id);?>

                  <?php $utils->show_input("csrf", $utils->sanitize($_SESSION['csrf']));?>
                  <div class="form-group">
                    <input type="text" name="oldUsername" id="oldUsername" value="<?php echo $data->username; ?>" hidden="">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="Username" name="Username" placeholder="Username" value="<?php echo $data->username; ?>">
                      <label for="Username">Username</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid Email" type="email" id="Email" name="Email" placeholder="Email Address" value="<?php echo $data->email; ?>" />
                      <label for="Email">Email Address</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="password" title="Must contain at least one number, one uppercase letter, lowercase letter, one special character, and at least 8 or more characters" pattern="(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$" id="Password" name="Password" placeholder="New Password">
                      <label for="Password">New Password</label>
                    </div>
                    <small>Keep it empty if you do not want change the password.</small>
                  </div>

                  <div class="form-group">
                    <div class="form-group">
                      <label for="switch-state">Enable 2FA: </label>
                      <a href="authsettings.php" class="btn btn-primary text-white">Open 2FA Settings</a>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-group">
                      <div class="form-group">
                        <label for="switch-state">Enable Security Question: </label>
                        <input class="bootstrap-switch" id="sqenable" name="sqenable" type="checkbox" data-size="small" <?php if ($user_question->sqenable == "on") {
    echo 'checked';
}?>>
                      </div>
                    </div>
                    <div>
                      <select name="questions" id="questions" class="form-control">
                        <?php foreach ($questions as $question): ?>
                          <option value="<?php echo $question ?>" <?php if ($user_question != null && $user_question->question == $question) {echo "selected";}?>><?php echo $question; ?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="answer" name="answer" placeholder="Answer the question" value="<?php if (!$user_question == null) {
    echo ($user_question->answer);
}?>" />
                      <label for="answer">Answer the question</label>
                    </div>
                  </div>
                  <button class="btn btn-primary btn-block">Update your information</button>
                </div>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <?php include_once 'components/footer.php';?>

  <?php include_once 'components/js.php';?>
  <script src="asset/js/bootstrap-switch/main.js"></script>
  <script src="asset/js/bootstrap-switch/highlight.js"></script>
  <script src="asset/js/bootstrap-switch/bootstrap-switch.js"></script>
</body>

</html>