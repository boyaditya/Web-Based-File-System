<?php
// getDetailFile.php

include_once("views/Template.view.php");
include_once("models/DB.class.php");
include_once("controllers/FileSystem.controller.php");

$controller = new FileSystemController();
$id = $_GET['id']; // Get the id from the query string
$file = $controller->getDetailFile($id);

// Output the file data as JSON
header('Content-Type: application/json');

if (isset($file) && !empty($file)) {
    // Remove the 'content' field from the array
    unset($file['content']);

    $json = json_encode($file);

    if (json_last_error() != JSON_ERROR_NONE) {
        echo 'JSON error: ' . json_last_error_msg();
    } else {
        header('Content-Type: application/json');
        echo $json;
    }
} else {
    echo '$file is not set or empty';
}

