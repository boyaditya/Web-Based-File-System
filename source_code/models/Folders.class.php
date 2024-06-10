<?php

class Folders extends DB
{
    function getFolders()
    {
        $query = "SELECT * FROM folders ORDER BY folder_id ASC";
        return $this->execute($query);
    }

    function getFolderById($id)
    {
        $query = "SELECT * FROM folders WHERE folder_id='$id'";
        return $this->execute($query);
    }

    function add($data)
    {
        $folderName = $data['folder_name'];
        $parentFolderId = $data['parent_folder_id'];

        $query = "INSERT INTO folders (folder_name, parent_folder_id) VALUES ('$folderName', $parentFolderId)";

        return $this->execute($query);
    }

    function delete($id)
    {
        $query = "DELETE FROM folders WHERE folder_id= '$id'";
        return $this->execute($query);
    }

    function update($data)
    {
        $id = $data['folder_id'];
        $folderName = $data['folder_name'];
        $parentFolderId = $data['parent_folder_id'];

        $query = "UPDATE folders SET folder_name='$folderName', parent_folder_id=$parentFolderId WHERE folder_id='$id'";

        return $this->execute($query);
    }

    function rename($data)
    {
        $id = $data['id'];
        $folderName = $data['name'];

        $query = "UPDATE folders SET folder_name='$folderName' WHERE folder_id='$id'";

        return $this->execute($query);
    }

    function checkAndCreateRootFolder()
    {
        $root_folder_check = $this->execute("SELECT * FROM folders WHERE folder_id = 0");
        if ($root_folder_check->num_rows == 0) {
            $this->execute("INSERT INTO folders (folder_id, folder_name, parent_folder_id) VALUES (1, 'root', NULL)");
        }
    }

    function getCurrentFolder($folder_id)
    {
        $query = "SELECT * FROM folders WHERE folder_id = $folder_id";
        return $this->execute($query)->fetch_assoc();
    }

    function getParentFolder($folder_id)
    {
        $current_folder = $this->getCurrentFolder($folder_id);
        $parent_folder_id = $current_folder  ? $current_folder['parent_folder_id'] : 0;
        return $this->getFolderById($parent_folder_id);
    }

    function getSubFolders($folder_id)
    {
        $query = "SELECT * FROM folders WHERE parent_folder_id = $folder_id";
        return $this->execute($query);
    }

    function getFilesInFolder($folder_id)
    {
        $query = "SELECT * FROM files WHERE folder_id = $folder_id";
        return $this->execute($query);
    }
}
