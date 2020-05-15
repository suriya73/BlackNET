<?php
include_once '../session.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
	if ($_SESSION['csrf'] != $utils->sanitize($_POST['csrf'])) {
		$utils->redirect("../changePassword.php?msg=csrf");
	} else {
		$id = $_POST['id'];
		$oldusername = $utils->sanitize($_POST['oldUsername']);
		$username = $utils->sanitize($_POST['Username']);
		$email = $utils->sanitize($_POST['Email']);
		$auth = isset($_POST['auth-state']) ? "on" : "off";
		$question = $utils->sanitize($_POST['questions']);
		$answer = $utils->sanitize($_POST['answer']);
		$sqenable = isset($_POST['sqenable']) ? "on" : "off";

		if (!$_POST['Password'] || $_POST['Password'] == "") {
			$password = "No change";
		} else {
			$password = $utils->sanitize($_POST['Password']);
		}
		$msg = $user->updateUser(
			$id,
			$oldusername,
			$username,
			$email,
			$password,
			$auth,
			$question,
			$answer,
			$sqenable
		);
		$utils->redirect("../changePassword.php?msg=yes");
	}
}
