<?php
include_once '../session.php';
include_once '../vendor/auth/FixedBitNotation.php';
include_once '../vendor/auth/GoogleAuthenticator.php';
include_once '../vendor/auth/GoogleQrUrl.php';

$g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
$status = isset($_POST['enable']) ? "on" : "off";
$code = isset($_POST['code']) ? $utils->sanitize($_POST['code']) : null;
$secret = isset($_POST['secret']) ? $utils->sanitize($_POST['secret']) : null;
$username = $utils->sanitize($_POST['username']);
$msg = [];

if ($_SESSION['csrf'] != $utils->sanitize($_POST['csrf'])) {
	$msg = ["msg" => "csrf", "code" => "error"];
} else {
	if ($status == "off") {
		$user->enables2fa($username, $secret, $status);
		$msg = ["msg" => "ok", "code" => "disable"];
	} else {
		if ($g->checkCode($secret, $code)) {
			$user->enables2fa($username, $secret, $status);
			$msg = ["msg" => "ok", "code" => "enable"];
		} else {
			$msg = ["msg" => "error", "code" => "error"];
		}
	}
}
$utils->redirect("../authsettings.php?msg=" . $utils->sanitize($msg['msg']) . "&code=" . $utils->sanitize($msg['code']));
