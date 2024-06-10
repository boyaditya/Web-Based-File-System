$(document).ready(function () {
  $("#customFile").on("change", function () {
    let fileName = $(this).val().split("\\").pop();
    $(this).next(".custom-file-label").addClass("selected").html(fileName);
  });
});

document.addEventListener("DOMContentLoaded", function () {
  var dotMenus = document.querySelectorAll(".dot-menu");
  var popupMenu = document.getElementById("popup-menu");

  dotMenus.forEach(function (dotMenu) {
    dotMenu.addEventListener("click", function (event) {
      event.stopPropagation();
      popupMenu.style.display = "block";
      var popupMenuWidth = popupMenu.offsetWidth;
      popupMenu.style.left = event.pageX - popupMenuWidth + "px"; // Modify this line
      popupMenu.style.top = event.pageY + "px";
    });
  });

  document.addEventListener("click", function () {
    popupMenu.style.display = "none";
  });
});

document.querySelectorAll(".table tbody tr td").forEach(function (td) {
  td.addEventListener("click", function () {
    window.location = td.querySelector("a").href;
  });
});

$(document).on("click", ".triple-dot", function () {
  const id = $(this).parent().data("id");
  console.log(id);
});

let breadcrumbItems = document.querySelectorAll(".breadcrumb-item a");
let path = Array.from(breadcrumbItems)
  .map((item) => item.textContent)
  .join("/");

console.log(path);

$(".triple-dot.files-item").on("click", function () {
  const id = $(this).parent().data("id");
  var type = "file";
  console.log(id);

  // Remove the previous click event handler
  $("#detail").off("click");

  // Add this code
  $("#detail").on("click", function () {
    $("#detailsContent").html(`
    <div class="modal-header">
      <h5 class="modal-title" id="detailsModalLabel">Details</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <table class="table table-sm">
        <tbody class="file-details">
          <!-- File details will be inserted here -->
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
    </div>
  `);

    $.ajax({
      type: "GET",
      url: "http://localhost:8080/application/Web-File-System/source_code/getDetailFile.php",
      dataType: "json",
      data: { id: id },
      success: function (data) {
        console.log(data);
        // Create the HTML for the file details
        var detailsHtml = `
        <tr>
          <th scope="row">Name</th>
          <td>${data.file_name}</td>
        </tr>
        <tr>
          <th scope="row">Type</th>
          <td>${data.file_type}</td>
        </tr>
        <tr>
          <th scope="row">Folder path</th>
          <td>${path}</td>
        </tr>
        <tr>
          <th scope="row">Size</th>
          <td>${data.file_size}</td>
        </tr>
        <tr>
          <th scope="row">Date crated</th>
          <td>${data.created_at}</td>
        </tr>
        <tr>
          <th scope="row">Date modified</th>
          <td>${data.updated_at}</td>
        </tr>
      `;

        // Update the modal with the file details
        $(".modal .file-details").html(detailsHtml);
      },
    });
  });
  // Remove the previous click event handler
  $("#rename").off("click");

  $("#rename").on("click", function () {
    $.ajax({
      type: "GET",
      url: "http://localhost:8080/application/Web-File-System/source_code/getDetailFile.php",
      dataType: "json",
      data: { id: id },
      success: function (data) {
        console.log(data);
        $("#id").val(data.file_id);
        $("#name").val(data.file_name);
        $("#rename_type").val(type);
      },
    });
  });

  $("#delete").on("click", function () {
    $("#delete_file_id").val(id);
  });
});

$(".triple-dot.folder-item").on("click", function () {
  const id = $(this).parent().data("id");
  var type = "folder";
  console.log(id);

  // Remove the previous click event handler
  $("#detail").off("click");

  // Add this code
  $("#detail").on("click", function () {
    $("#detailsContent").html(`
    <div class="modal-header">
      <h5 class="modal-title" id="detailsModalLabel">Details</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <table class="table table-sm">
        <tbody class="folder-details">
          <!-- Folder details will be inserted here -->
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
    </div>
  `);

    $.ajax({
      type: "GET",
      url: "http://localhost:8080/application/Web-File-System/source_code/getDetailFolder.php",
      dataType: "json",
      data: { id: id },
      success: function (data) {
        console.log(data);
        // Create the HTML for the folder details
        var detailsHtml = `
        <tr>
          <th scope="row">Name</th>
          <td>${data.folder_name}</td>
        </tr>
        <tr>
          <th scope="row">Folder path</th>
          <td>${path}</td>
        </tr>
        <tr>
          <th scope="row">Date crated</th>
          <td>${data.created_at}</td>
        </tr>
      `;

        // Update the modal with the folder details
        $(".modal .folder-details").html(detailsHtml);
      },
    });
  });
  // Remove the previous click event handler
  $("#rename").off("click");

  $("#rename").on("click", function () {
    $.ajax({
      type: "GET",
      url: "http://localhost:8080/application/Web-File-System/source_code/getDetailFolder.php",
      dataType: "json",
      data: { id: id },
      success: function (data) {
        console.log(data);
        $("#id").val(data.folder_id);
        $("#name").val(data.folder_name);
        $("#rename_type").val(type);
      },
    });
  });

  $("#delete").on("click", function () {
    $("#delete_id").val(id);
    $("#delete_type").val(type);
  });
});
