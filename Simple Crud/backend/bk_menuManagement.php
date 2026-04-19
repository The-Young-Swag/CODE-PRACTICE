<?php
// bk_user_management.php — User Management Backend
// Table: user_management
// file_put_contents(__DIR__."/debug.txt", print_r($_POST,true));

file_put_contents(__DIR__."/debug_post.txt", print_r($_POST, true));
$pdo = require __DIR__ . "/../db/db.php";

date_default_timezone_set("Asia/Manila");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "POST only."]);
    exit;
}

function MenuTable(PDO $pdo){
    $rows = $pdo->query(
        "SELECT menu_id, menu_name, menu_link, menu_order, created_at
        FROM menu
        ORDER  BY menu_id "
    )->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo json_encode(["html" => "<tr><td colspan='7' class='text-center text-muted py-4'>No menu  found.</td></tr>"]); exit;
    }

    $html = " ";
    foreach ($rows as $row){
        $menuID = htmlspecialchars($row["menu_id"], ENT_QUOTES, "UTF-8");
        $menuName = htmlspecialchars($row["menu_name"], ENT_QUOTES, "UTF-8");
        $menuLink = htmlspecialchars($row["menu_link"], ENT_QUOTES, "UTF-8");
        $menuOrder = htmlspecialchars($row["menu_order"], ENT_QUOTES, "UTF-8");
        $date = htmlspecialchars($row["created_at"], ENT_QUOTES, "UTF-8");
    
        $html .= "
        <tr data-id='{$menuID}'>
            <td>{$menuID}</td>
            <td>{$menuName}</td>
            <td>{$menuLink}</td>
            <td>{$menuOrder}</td>
            <td>{$date}</td>
            <td>
                <button data-action='edit' data-id='<?= $menuID ?>' class='btn btn-sm btn-outline-primary btn-edit-user'>
                    <i class='bi bi-pencil'></i> Edit
                </button>
                <button data-action='delete' data-id='<?= $menuID ?>' class='btn btn-sm btn-outline-danger btn-delete-user'>
                    <i class='bi bi-pencil'></i> Delete
                </button>
       
            </td>
        </tr>";
        }
        echo json_encode(["html" => $html]); exit;
}


function AddMenu(PDO $pdo): void
{
    $menuID = trim($_POST["menu_id"]  ?? "");
    $menuName = trim($_POST["menu_name"]  ?? "");
    $menuLink = (int) ($_POST["menu_link"] ?? 0);
    $menuOrder = $_POST["menu_order"] ?? "";

    if (!$menuID || !$menuName || !$menuLink || !$menuOrder) {
        echo json_encode(["error" => "All fields are required."]); exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO user_management (username, fullname, role_id, password_hash)
         VALUES (:username, :fullname, :role_id, :password_hash)"
    );
    $stmt->execute([
        ":menu_id" => $menuID,
        ":menu_name" => $menuName,
        ":menu_link" => $menuLink,
        ":menu_order" => $menuOrder,
    ]);

    echo json_encode(["success" => true, "menu_id" => (int) $pdo->lastInsertId()]); exit;
}


function EditMenu(PDO $pdo): void
{
    $userId   = (int)  ($_POST["user_id"]  ?? 0);
    $username = trim(   $_POST["username"] ?? "");
    $fullname = trim(   $_POST["fullname"] ?? "");
    $roleId   = (int)  ($_POST["role_id"]  ?? 0);

    if (!$userId || !$username || !$fullname || !$roleId) {
        echo json_encode(["error" => "All fields are required."]); exit;
    }

    $stmt = $pdo->prepare(
        "UPDATE user_management
         SET    username = :username,
                fullname = :fullname,
                role_id  = :role_id
         WHERE  user_id  = :user_id"
    );
    $stmt->execute([
        ":username" => $username,
        ":fullname" => $fullname,
        ":role_id"  => $roleId,
        ":user_id"  => $userId,
    ]);

    echo json_encode(["success" => true]); exit;
}




$request = trim($_POST['request'] ?? '');

switch ($request) {
    case "getMenuTable":
        MenuTable($pdo); 
        break;
    case "addMenu":
        AddMenu($pdo); 
        break;

    case "editMenu":
        EditMenu($pdo); 
        break;
    
    default:
        echo json_encode(["error" => "Unknown request: {$request}"]);
        exit;
}