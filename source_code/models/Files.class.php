<?php

class Files extends DB
{
    function getFiles()
    {
        $query = "SELECT * FROM files ORDER BY file_id ASC";
        return $this->execute($query);
    }

    function getFileById($id)
    {
        $query = "SELECT * FROM files WHERE file_id='$id'";
        return $this->execute($query);
    }

    function add($data)
    {
        $fileName = $data['file_name'];
        $fileType = $data['file_type'];
        $fileSize = $data['file_size'];
        $content = $data['content'];
        $folderId = $data['folder_id'];

        $query = "INSERT INTO files (file_name, file_type, file_size, content, folder_id, owner_id) VALUES ('$fileName', '$fileType', '$fileSize', '$content', $folderId)";

        return $this->execute($query);
    }

    function delete($id)
    {
        $query = "DELETE FROM files WHERE file_id= '$id'";
        return $this->execute($query);
    }

    function update($data)
    {
        $id = $data['file_id'];
        $fileName = $data['file_name'];
        $fileType = $data['file_type'];
        $fileSize = $data['file_size'];
        $content = $data['content'];
        $folderId = $data['folder_id'];

        $query = "UPDATE files SET file_name='$fileName', file_type='$fileType', file_size='$fileSize', content='$content', folder_id=$folderId WHERE file_id='$id'";

        return $this->execute($query);
    }

    function rename($data)
    {
        $id = $data['id'];
        $fileName = $data['name'];

        $query = "UPDATE files SET file_name='$fileName' WHERE file_id='$id'";

        return $this->execute($query);
    }

    function upload($data, $file)
    {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileType = $file['type'];
        $fileSize = $file['size'];
        $folderId = (int)$data['folder_id'];

        // Define a directory to store the file
        $uploadDir = 'uploads/';

        // Create the directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Define the file path
        $filePath = $uploadDir . $fileName;

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($fileTmpName, $filePath)) {
            $query = "INSERT INTO files (file_name, file_type, file_size, content, folder_id) 
                      VALUES ('$fileName', '$fileType', '$fileSize', '$filePath', $folderId)";

            if ($this->execute($query)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
  
}
