<?php
header('Content-type: text/html; charset=UTF-8');

include_once 'classes/POST.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        $POST = new POST;
        $folder_name = isset($_POST['folder_name']) && $_POST['folder_name'] != "" ? $_POST['folder_name'] : 'www';
        $file_name = isset($_POST['file_name']) ? $_POST['file_name'] : "unknown.txt";
        $data = $POST->sanitize($_POST['data']);
        $POST->prepare($folder_name, $file_name, $data);
        $POST->write();
    } catch (Exception $e) {

    }
}
