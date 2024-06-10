<?php
// getDetailFile.php

include_once("views/Template.view.php");
include_once("models/DB.class.php");
include_once("controllers/FileSystem.controller.php");

$controller = new FileSystemController();
$id = $_GET['id']; // Get the id from the query string
$folder = $controller->getDetailFolder($id);

// Output the folder data as JSON
header('Content-Type: application/json');

if (isset($folder) && !empty($folder)) {
    $json = json_encode($folder);

    if (json_last_error() != JSON_ERROR_NONE) {
        echo 'JSON error: ' . json_last_error_msg();
    } else {
        header('Content-Type: application/json');
        echo $json;
    }
} else {
    echo '$folder is not set or empty';
}

