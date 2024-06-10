<?php

include_once("views/Template.view.php");
include_once("models/DB.class.php");
include_once("controllers/FileSystem.controller.php");

$fileSystemController = new FileSystemController();

$current_folder_id = 0;

if (isset($_GET['folder_id'])) {
    $current_folder_id = $_GET['folder_id'];
}

if (isset($_POST['upload'])) {
    $fileSystemController->uploadFile($_POST, $_FILES['file'], $current_folder_id);
}

if (isset($_POST['rename'])) {
    $type = $_POST['rename_type'];
    if ($type == 'folder') {
        $fileSystemController->renameFolder($_POST);
    } elseif ($type == 'file') {
        $fileSystemController->renameFile($_POST);
    }
}


if (isset($_POST['delete'])) {
    $type = $_POST['delete_type'];
    if ($type == 'folder') {
        $fileSystemController->deleteFolder($_POST['id']);
    } elseif ($type == 'file') {
        $fileSystemController->deleteFile($_POST['id']);
    }
}

if (isset($_POST['create_folder'])) {
    $fileSystemController->createFolder($_POST);
}

$fileSystemController->index();
