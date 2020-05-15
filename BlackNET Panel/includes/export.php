<?php
include_once '../classes/Database.php';
include_once '../classes/Clients.php';
$client = new Clients;
$logs = $client->getLogs();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=System_Logs_' . date("Y-m-d") . '.csv');
$output = fopen('php://output', 'w');
fputcsv($output, array('No', 'Log Time', 'Client ID', 'Log Type', 'Log Message'));

if (count($logs) > 0) {
    foreach (json_decode(json_encode($logs),true) as $log) {
        fputcsv($output, $log);
    }
}

fclose($output);
?>
