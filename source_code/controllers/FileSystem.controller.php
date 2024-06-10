<?php
include_once("conf.php");
include_once("models/Folders.class.php");
include_once("models/Files.class.php");
include_once("views/FileSystem.view.php");

class FileSystemController
{
  private $files;
  private $folders;

  function __construct()
  {
    $this->files = new Files(Conf::$db_host, Conf::$db_user, Conf::$db_pass, Conf::$db_name);
    $this->folders = new Folders(Conf::$db_host, Conf::$db_user, Conf::$db_pass, Conf::$db_name);
  }


  public function index()
  {
    $this->folders->open();
    $this->folders->checkAndCreateRootFolder();

    $current_folder_id = isset($_GET['folder_id']) ? (int)$_GET['folder_id'] : 0;

    $current_folder = $this->folders->getCurrentFolder($current_folder_id);

    $parent_folder_id = $current_folder ? $current_folder['parent_folder_id'] : 0;

    $folders = $this->folders->getSubFolders($current_folder_id);
    $dataFolders = array();
    while ($row = $folders->fetch_assoc()) {
      array_push($dataFolders, $row);
    }

    $this->files->open();
    $files = $this->folders->getFilesInFolder($current_folder_id);

    $dataFiles = array();
    while ($row = $files->fetch_assoc()) {
      array_push($dataFiles, $row);
    }

    $parent_folder = $this->folders->getParentFolder($current_folder_id)->fetch_assoc();

  
    $pathSegments = [];
    $folder_id = $current_folder_id;
    while ($folder_id != null) {
      $folder = $this->folders->getCurrentFolder($folder_id);
      array_unshift($pathSegments, ['id' => $folder_id, 'name' => $folder['folder_name']]);
      $folder_id = $folder['parent_folder_id'];
    }

    if (empty($pathSegments)) {
      $pathSegments[] = ['id' => 0, 'name' => 'root'];
    }

    // $pathSegments = $this->getFolderPath($current_folder_id);

    $this->files->close();
    $this->folders->close();

    $view = new FileSystemView();
    $view->render($dataFolders, $dataFiles, $current_folder_id, $parent_folder_id, $parent_folder, $pathSegments);
  }

  public function getCurrentFolderId($get)
  {
    return isset($get['folder_id']) ? (int)$get['folder_id'] : 0;
  }

  public function getFolderPath($current_folder_id)
  {
    $pathSegments = [];
    $folder_id = $current_folder_id;
    while ($folder_id != null) {
      $folder = $this->folders->getCurrentFolder($folder_id);
      array_unshift($pathSegments, ['id' => $folder_id, 'name' => $folder['folder_name']]);
      $folder_id = $folder['parent_folder_id'];
    }

    if (empty($pathSegments)) {
      $pathSegments[] = ['id' => 0, 'name' => 'root'];
    }

    return $pathSegments;
  }


  function createFolder($data)
  {
    $this->folders->open();
    $this->folders->add($data);
    $this->folders->close();

    header("location:index.php");
  }

  function editFolder($data)
  {
    $this->folders->open();
    $this->folders->update($data);
    $this->folders->close();

    header("location:index.php");
  }

  function deleteFolder($id)
  {
    $this->folders->open();
    $this->folders->delete($id);
    $this->folders->close();

    header("location:index.php");
  }

  function renameFolder($data)
  {
    $this->folders->open();
    $this->folders->rename($data);
    $this->folders->close();

    header("location:index.php");
  }

  function createFile($data)
  {
    $this->files->open();
    $this->files->add($data);
    $this->files->close();

    header("location:index.php");
  }

  function editFile($data)
  {
    $this->files->open();
    $this->files->update($data);
    $this->files->close();

    header("location:index.php");
  }

  function renameFile($data)
  {
    $this->files->open();
    $this->files->rename($data);
    $this->files->close();

    header("location:index.php");
  }

  function deleteFile($id)
  {
    $this->files->open();
    $this->files->delete($id);
    $this->files->close();

    header("location:index.php");
  }

  function uploadFile($data, $file, $folderId)
  {
    $this->files->open();
    $this->files->upload($data, $file);
    $this->files->close();

    header("Location: index.php?folder_id=$folderId");
  }

  function getDetailFile($id)
  {
    $this->files->open();
    $file = $this->files->getFileById($id)->fetch_assoc();

    $this->files->close();

    return $file;
  }

  function getDetailFolder($id)
  {
    $this->folders->open();
    $folder = $this->folders->getFolderById($id)->fetch_assoc();

    $this->folders->close();

    return $folder;
  }


  public function getFileOrFolderDetails()
  {
    $id = $_GET['id']; // Get the id from the query string

    $this->files->open();
    $this->folders->open();

    $file = $this->files->getFileById($id)->fetch_assoc();
    $folder = $this->folders->getFolderById($id)->fetch_assoc();

    $this->files->close();
    $this->folders->close();

    // Output the file or folder data as JSON
    header('Content-Type: application/json');
    echo json_encode($file ? $file : $folder);
    exit;
  }
}