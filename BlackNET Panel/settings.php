<?php
include_once 'session.php';
include_once 'classes/Settings.php';
include_once 'classes/Mailer.php';

$smtp = new Mailer();
$getSMTP = $smtp->getSMTP(1);

$settings = new Settings;
$getSettings = $settings->getSettings(1);

$smtp_types = ["None", "SSL", "TLS"];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include_once 'components/meta.php';?>
  <title>BlackNET - Network Settings</title>
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
            Update Settings</div>
          <div class="card-body">
            <form id="Form1" name="Form1" method="POST" action="includes/updateSettings.php">

              <div class="container container-special">
                <?php if (isset($_GET['msg']) && $_GET['msg'] === "yes"): ?>
                  <?php $utils->show_alert("Settings Has Been Updated", "success", "check-circle");?>
                <?php endif;?>

                <?php if (isset($_GET['msg']) && $_GET['msg'] === "csrf"): ?>
                  <?php $utils->show_alert("CSRF Token is invalid.", "danger", "times-circle");?>
                <?php endif;?>
              </div>
              <div class="container container-special">
                <div class="align-content-center justify-content-center">
                  <?php $utils->show_input("csrf", $utils->sanitize($_SESSION['csrf']));?>

                  <?php $utils->show_input("id", $getSettings->id);?>
                  <div class="form-group">
                    <div class="form-group">
                      <label for="switch-state">Panel Status: </label>
                      <input class="bootstrap-switch" id="panel-state" name="panel-state" type="checkbox" data-size="small" <?php if ($getSettings->panel_status == "on") {
    echo 'checked';
}?>>
                    </div>
                  </div>
                  <hr>
                  <div class="form-group">
                    <div class="form-group">
                      <label for="switch-state">Enable reCAPTCHA: </label>
                      <input class="bootstrap-switch" id="status-state" name="status-state" type="checkbox" data-size="small" <?php if ($getSettings->recaptchastatus == "on") {
    echo 'checked';
}?>>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="reCaptchaPublic" name="reCaptchaPublic" placeholder="reCAPTCHA Public Key" value="<?php echo $getSettings->recaptchapublic; ?>">
                      <label for="reCaptchaPublic">reCAPTCHA Public Key</label>
                    </div>
                  </div>

                  <div class="form-group">
                    <div class="form-label-group">
                      <input class="form-control" type="text" id="reCaptchaPrivate" name="reCaptchaPrivate" placeholder="reCAPTCHA Public Key" value="<?php echo $getSettings->recaptchaprivate; ?>">
                      <label for="reCaptchaPrivate">reCAPTCHA Private Key</label>
                    </div>
                  </div>


                  <button for="Form1" name="Form1" class="btn btn-primary btn-block">Update Settings</button>
                </div>
                <hr>
              </div>
            </form>

            <form id="Form2" name="Form2" method="POST" action="includes/updateSettings.php" class="pt-2">
              <div class="container container-special" class="align-content-center justify-content-center">
                  <?php $utils->show_input("csrf", $utils->sanitize($_SESSION['csrf']));?>

                  <?php $utils->show_input("id", $getSMTP->id);?>
                <div class="form-group">
                  <div class="form-group">
                    <label for="switch-state">Enable SMTP: </label>
                    <input class="bootstrap-switch" id="smtp-state" name="smtp-state" type="checkbox" data-size="small" <?php if ($getSMTP->status == "on") {
    echo 'checked';
}?>>
                  </div>
                </div>

                <div class="form-group">
                  <div class="form-label-group">
                    <input class="form-control" type="text" id="SMTPHost" name="SMTPHost" placeholder="SMTP Host" value="<?php echo $getSMTP->smtphost; ?>">
                    <label for="SMTPHost">SMTP Host</label>
                  </div>
                </div>

                <div class="form-group">
                  <div class="form-label-group">
                    <input class="form-control" type="text" id="SMTPUser" name="SMTPUser" placeholder="SMTP User" value="<?php echo $getSMTP->smtpuser; ?>">
                    <label for="SMTPUser">SMTP User</label>
                  </div>
                </div>

                <div class="form-group">
                  <div class="form-label-group">
                    <input class="form-control" type="password" id="SMTPPassword" name="SMTPPassword" placeholder="SMTP Password" value="<?php echo base64_decode($getSMTP->smtppassword); ?>">
                    <label for="SMTPPassword">SMTP Password</label>
                  </div>
                </div>

                <div class="form-group">
                  <select label="Select a Security type" name="security" id="security" class="form-control">
                    <option>Select a Security type</option>
                    <?php foreach ($smtp_types as $smtp_type): ?>
                    <option value="<?php echo strtolower($smtp_type); ?>" <?php if ($getSMTP->security_type == strtolower($smtp_type)) {echo "selected";}?>><?php echo $smtp_type ?></option>
                  <?php endforeach;?>
                  </select>
                </div>

                <div class="form-group">
                  <div class="form-label-group">
                    <input class="form-control" type="text" id="SMTPPort" name="SMTPPort" placeholder="SMTP Port" value="<?php echo $getSMTP->port; ?>">
                    <label for="SMTPPort">SMTP Port</label>
                  </div>
                </div>
                <button for="Form2" name="Form2" class="btn btn-primary btn-block">Update SMTP</button>
              </div>
            </form>
          </div>

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