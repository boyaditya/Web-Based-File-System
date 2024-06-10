<?php include 'header.php' ?>

<style>
  .table tbody tr:hover {
    cursor: pointer;
    background-color: rgb(234, 232, 255);
  }

  a {
    color: black;
    text-decoration: none;
  }

  .upload-panel {
    background-color: #f1f3f4;
    padding: 20px;
    border-radius: 5px;
    height: fit-content;
  }

  .upload-panel h4 {
    margin-bottom: 20px;
  }

  .folder-icon {
    color: #f0ad4e;
  }

  .file-icon {
    color: #808080;
  }

  #popup-menu {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ccc;
    padding: 0px;
    z-index: 1000;
    width: 200px;
  }

  .dot-menu {
    cursor: pointer;
    padding: 20px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .dot-menu:hover {
    background-color: #e0e0e0;
  }

  #popup-menu a {
    padding: 5px 10px;
    display: flex;
    align-items: center;
  }

  #popup-menu a:hover {
    background-color: #e0e0e0;
    color: inherit;
    /* Keeps the color the same on hover */
    text-decoration: none;
    /* Removes the underline on hover */
  }

  .breadcrumb-item {
    font-size: 1.3em;
    /* Adjust this value to your liking */
  }

  #popup-menu i,
  table i {
    font-size: 1em;
    /* Adjust this value to your liking */
    margin-right: 10px;
    width: 20px;
    /* Add this line to make the icons the same width */
    text-align: center;
    /* Add this line to center the icons */
  }

  .big-icon {
    font-size: 1.4em;
    /* Adjust this value to your liking */
  }

  .triple-dot {
    padding: 10px;
    font-size: 1em;
    /* Adjust this value to the original size */
    margin-right: 0;
    /* Adjust this value to the original margin */
    width: auto;
    /* Reset the width */
    text-align: left;
    /* Reset the text alignment */

  }

  .triple-dot>img {
    width: 100%;
    height: 100%;
  }

  #popup-menu .fa {
    color: #333;
  }
</style>

<div id='popup-menu' style='display: none;'>
  <a href='#' data-toggle="modal" data-target="#detailsModal" id='detail'><i class="fa fa-info-circle tampilModalDetail"></i>Details</a>
  <a href='#' data-toggle="modal" data-target="#renameModal" id='rename'><i class="fa fa-edit"></i>Rename</a>
  <a href='#' data-toggle="modal" data-target="#deleteModal" id='delete'><i class="fa fa-trash"></i>Delete</a>
</div>

<div class="container-fluid my-4">
  <div class="row">
    <!-- Form untuk upload file -->
    <div class="col-md-3">
      <div class="upload-panel">
        <h4>Create New Folder</h4>
        <form action="index.php" method="post" class="mb-4">
          <div class="form-group">
            <label for="folderName">Folder Name:</label>
            <input type="text" class="form-control" name="folder_name" id="folderName" required>
          </div>
          <button type="submit" name="create_folder" class="btn btn-primary mt-3">Create</button>
          <input type="hidden" name="parent_folder_id" value="CURRENT_FOLDER_ID">
        </form>

        <hr>

        <h4>Upload File</h4>
        <form action="index.php" method="post" enctype="multipart/form-data" class="mb-4">
          <div class="custom-file">
            <input type="file" class="custom-file-input" name="file" id="customFile" required>
            <label class="custom-file-label" for="customFile">Choose file</label>
          </div>
          <button type="submit" name="upload" class="btn btn-primary mt-3">Upload</button>
          <input type="hidden" name="folder_id" value="CURRENT_FOLDER_ID">
        </form>
      </div>
    </div>

    <div class="col-md-9">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          PATH
        </ol>
      </nav>

      <table class="table">
        <thead>
          <tr>
            <th>Name</th>
            <th></th>
            <th>Date Modified</th>
            <th>Type</th>
            <th>Size</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          DATA_ITEMS
        </tbody>
      </table>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" id="detailsContent">

    </div>
  </div>
</div>

<!-- Rename Modal -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-labelledby="renameModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="renameModalLabel">Rename</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="renameForm" action="index.php" method="post">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
          <input type="hidden" id="id" name="id">
          <input type="hidden" id="rename_type" name="rename_type">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" name="rename" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Delete</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this?
        <form id="deleteForm" action="index.php" method="post">
          <input type="hidden" id="delete_id" name="id">
          <input type="hidden" id="delete_type" name="delete_type">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="deleteForm" name="delete" class="btn btn-danger">Delete</button>
      </div>
      </form>
    </div>
  </div>
</div>

<?php include 'footer.php' ?>