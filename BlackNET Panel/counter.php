<?php
header("Content-Type: application/json");

include_once 'classes/Database.php';
include_once 'classes/Clients.php';
include_once 'getcontery.php';

$counter = new Clients;
$arrays = [];
foreach ($countries as $data => $value) {
	array_push($arrays, ["id" => $data, "value"=>$counter->countClientsByCond("country",$data)]);
}
echo json_encode(["countries" => $arrays]);
