<?php
class FileSystemView
{
  public function render($dataFolders, $dataFiles, $current_folder_id, $parent_folder_id, $parent_folder, $pathSegments)
  {

    $pathHtml = '';
    foreach ($pathSegments as $segment) {
      $pathHtml .= "<li class='breadcrumb-item'><a href='index.php?folder_id={$segment['id']}'>{$segment['name']}</a></li>";
    }

    $dataItems = "";

    // Add folders
    foreach ($dataFolders as $folder) {
      $timestamp = strtotime($folder['updated_at']); // Convert the date and time string to a Unix timestamp
      $dateModified = date('m/d/Y H:i', $timestamp); // Format the date

      $dataItems .= "<tr>";
      $dataItems .= "<td><a href='index.php?folder_id={$folder['folder_id']}'><i class='fa fa-folder folder-icon big-icon'></i> {$folder['folder_name']}</a></td>"; // Folder icon and name
      $dataItems .= "<td><a href='index.php?folder_id={$folder['folder_id']}'></a></td>"; // Empty cell
      $dataItems .= "<td><a href='index.php?folder_id={$folder['folder_id']}'>{$dateModified}</a></td>"; // Date modified
      $dataItems .= "<td><a href='index.php?folder_id={$folder['folder_id']}'>File folder</a></td>"; // Folder type
      $dataItems .= "<td><a href='index.php?folder_id={$folder['folder_id']}'>—</a></td>"; // Empty size for folders

      $dataItems .= "<td><div class='dot-menu' data-id='{$folder['folder_id']}'><i class='fa fa-ellipsis-v triple-dot folder-item'></i></div></td>";

      $dataItems .= "</tr>";
    }


    // Add files
    foreach ($dataFiles as $file) {
      $timestamp = strtotime($file['updated_at']); // Convert the date and time string to a Unix timestamp
      $dateModified = date('m/d/Y H:i', $timestamp); // Format the date
      $dataItems .= "<tr>";

      // Determine the file type and set the appropriate icon class
      $iconClass = '';
      $fileType = explode('/', $file['file_type'])[1]; // Extract the extension from the MIME type
      switch ($fileType) {
        case 'pdf':
          $iconClass = 'fa-file-pdf';
          break;
        case 'vnd.openxmlformats-officedocument.word':
        case 'word':
          $iconClass = 'fa-file-word';
          break;
        case 'vnd.ms-excel':
        case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
          $iconClass = 'fa-file-excel';
          break;
        case 'vnd.ms-powerpoint':
        case 'vnd.openxmlformats-officedocument.presentationml.presentation':
          $iconClass = 'fa-file-powerpoint';
          break;
        case 'jpeg':
        case 'png':
        case 'gif':
          $iconClass = 'fa-file-image';
          break;
        case 'zip':
        case 'x-rar':
          $iconClass = 'fa-file-archive';
          break;
        default:
          $iconClass = 'fa-file';
      }

      $dataItems .= "<td><a href='index.php?file_id={$file['file_id']}'><i class='fa {$iconClass} big-icon file-icon'></i> {$file['file_name']}</a></td>"; // File icon and name
      $dataItems .= "<td></td>"; // Empty cell
      $dataItems .= "<td>{$dateModified}</td>"; // Date modified
      $dataItems .= "<td>{$file['file_type']}</td>"; // File type

      $fileSizeInKB = round($file['file_size'] / 1024, 2);
      $dataItems .= "<td>{$fileSizeInKB} KB</td>"; // File size in kilobytes, rounded to 2 decimal places

      $dataItems .= "<td><div class='dot-menu' data-id='{$file['file_id']}'><i class='fa fa-ellipsis-v triple-dot files-item'></i></div></td>";

      $dataItems .= "</tr>";
    }



    $tpl = new Template("templates/home.php");

    $tpl->replace("DATA_ITEMS", $dataItems);
    $tpl->replace("CURRENT_FOLDER_ID", $current_folder_id);
    $tpl->replace("PATH", $pathHtml);
    $tpl->write();
  }
}