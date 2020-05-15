<?php
include_once '../session.php';
include_once '../classes/Clients.php';

if ($_SESSION['csrf'] != $utils->sanitize($_POST['csrf'])) {
	$utils->redirect("../viewlogs.php?msg=csrf");
} else {
	$client = new Clients;
	if (isset($_POST['log'])) {
		foreach ($_POST['log'] as $logs) {
			$client->deleteLog($logs);
		}
	}

	$utils->redirect("../viewlogs.php?msg=yes");
}
