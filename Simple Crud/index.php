<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Management</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">User Management</h5>
    <button class="btn btn-primary" id="btnAddUser">
      <i class="bi bi-plus-lg"></i> Add User
    </button>
  </div>

  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Full Name</th>
        <th>Role</th>
        <th class="text-center">Active</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="displayUser"></tbody>
  </table>

  <!-- ─── MENU TABLE ─────────────────────────────────────────────────── -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">Menu Management</h5>
    <button class="btn btn-primary" id="btnAddMenu">
      <i class="bi bi-plus-lg"></i> Add Menu
    </button>
  </div>
  <table class="table table-bordered table-hover">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Menu Name</th>
        <th>Link</th>
        <th>Menu Order</th>
        <th>Created</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="menuTable"></tbody>
  </table>

  <?php include "modal.php" ?>


  <script>
$(function () {
  loadMenuTable();


// Load Menu Table → bk_menuManagement.php
function loadMenuTable(){
  $.ajax({
    type: "POST",
    url: "backend/bk_menuManagement.php",
    data: { request: "getMenuTable" },
    dataType: "json",
    success: function(dataResult) {
        if (dataResult.html) {
            $("#menuTable").html(dataResult.html);  
        }
    },
    error: function(xhr, status, error) {
        console.error("Menu AJAX Error:", error, xhr.responseText);
    }
});
}

function openModal({ title, body, footer }) {
    $("#modalContent").html(`
        <div class="modal-header">
            <h4 class="modal-title">${title}</h4>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
            ${body}
        </div>

        <div class="modal-footer">
            ${footer ?? `<button class="btn btn-danger" data-bs-dismiss="modal">Close</button>`}
        </div>
    `);

    const modal = new bootstrap.Modal(document.getElementById('universalModal'));
modal.show();
}

function addMenuModal() {

    const body = `
        <form id="addMenuForm">
            <div class="mb-2">
                <label>Menu ID</label>
                <input type="text" name="menu_id" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Menu Name</label>
                <input type="text" name="menu_name" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Menu Link</label>
                <input type="text" name="menu_link" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Menu Order</label>
                <input type="number" name="menu_order" class="form-control" required>
            </div>
        </form>
    `;

    const footer = `
        <button class="btn btn-primary" onclick="submitAddMenu()">Save</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    `;

    openModal({
        title: "Add Menu",
        body,
        footer
    });
}


function editMenuModal() {

    const body = `
        <form id="addMenuForm">
            <div class="mb-2">
                <label>Menu ID</label>
                <input type="text" name="menu_id" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Menu Name</label>
                <input type="text" name="menu_name" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Menu Link</label>
                <input type="text" name="menu_link" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Menu Order</label>
                <input type="number" name="menu_order" class="form-control" required>
            </div>
        </form>
    `;

    const footer = `
        <button class="btn btn-primary" onclick="submitAddMenu()">Save</button>
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
    `;

    openModal({
        title: "Edit Menu",
        body,
        footer
    });
}

function submitEditMenu() {
    const formData = $("#addMenuForm").serialize();

    $.ajax({
        type: "POST",
        url: "backend/bk_menuManagement.php",
        data: formData + "&request=editMenu",
        dataType: "json",
        success: function(res) {

            if (res.error) {
                alert(res.error);
                return;
            }

            if (res.success) {
                $("#universalModal").modal("hide");

                loadMenuTable(); // refresh table
            }
        },
        error: function(xhr) {
            console.error("Edit Menu Error:", xhr.responseText);
        }
    });
}


// Load User Table → bk_dashboard.php
$.ajax({
    type: "POST",
    url: "backend/bk_dashboard.php", 
    data: { request: "getUserTable" },
    dataType: "json",
    success: function(dataResult) {
        if (dataResult.html) {
            $("#displayUser").html(dataResult.html);  
        }
    },
    error: function(xhr, status, error) {
        console.error("User AJAX Error:", error, xhr.responseText);
    }
});







//show add Menu
$("#btnAddMenu").on("click", addMenuModal);

//show edit Menu
$("#menuTable").on("click", "button[data-action='edit']", function () {
    const id = $(this).data("id");
    editMenuModal(id);
});
});






  </script>
</body>
</html>