<?php
include_once '../session.php';
include_once '../classes/Settings.php';
include_once '../classes/Mailer.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	$status = null;
	if (isset($_POST['Form1'])) {
		$settings = new Settings;
		if ($_SESSION['csrf'] != $utils->sanitize($_POST['csrf'])) {
			$status = "csrf";
		} else {
			$id = $_POST['id'];
			$recaptchaprivate = $utils->sanitize($_POST['reCaptchaPrivate']);
			$recaptchapublic = $utils->sanitize($_POST['reCaptchaPublic']);
			$status = isset($_POST['status-state']) ? "on" : 'off';
			$panel = isset($_POST['panel-state']) ? "on" : 'off';
			$msg = $settings->updateSettings(
				$id,
				$recaptchaprivate,
				$recaptchapublic,
				$status,
				$panel
			);
			$status = "yes";
		}
	}


	if (isset($_POST['Form2'])) {
		$smtp = new Mailer;
		if ($_SESSION['csrf'] != $utils->sanitize($_POST['csrf'])) {
			$status = "csrf";
		} else {
			$status = isset($_POST['smtp-state']) ? "on" : 'off';
			$msg = $smtp->setSMTP(
				$utils->sanitize($_POST['id']),
				$utils->sanitize($_POST['SMTPHost']),
				$utils->sanitize($_POST['SMTPUser']),
				$utils->sanitize($_POST['SMTPPassword']),
				$utils->sanitize($_POST['SMTPPort']),
				$utils->sanitize($_POST['security']),
				$status
			);
			$status = "yes";
		}
	}

	$utils->redirect("../settings.php?msg=" . $utils->sanitize($status));
}
