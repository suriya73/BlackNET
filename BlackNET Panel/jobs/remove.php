#!/usr/bin/env php
<?php
include_once realpath(__DIR__ . '/../classes/Database.php');
include_once realpath(__DIR__ . '/../classes/Clients.php');

$clients = new Clients;
if ($clients->uninstallOfflineClients()) {
    echo "Job Executed";
}
