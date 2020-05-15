<?php
include_once 'classes/Database.php';
include_once 'classes/Settings.php';

try {
	$settings = new Settings;
	$getSettings = $settings->getSettings(1);
	if ($getSettings->panel_status == "on") {
		echo "Panel Enabled";
	} else {
		echo "Panel Disabled";
	}
} catch (\Throwable $th) {
	//throw $th;
}
