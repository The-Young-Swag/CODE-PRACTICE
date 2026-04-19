<?php
// bk_user_management.php — User Management Backend
// Table: user_management

$pdo = require __DIR__ . "/../db/db.php";

date_default_timezone_set("Asia/Manila");
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "POST only."]); exit;
}

// ── Handlers ──────────────────────────────────────────────────────────────────

function UserTable(PDO $pdo): void
{
    $rows = $pdo->query(
        "SELECT user_id, username, fullname, role_id, is_active, created_at
         FROM   user_management
         ORDER  BY user_id"
    )->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo json_encode(["html" => "<tr><td colspan='7' class='text-center text-muted py-4'>No users found.</td></tr>"]); exit;
    }

    $html = "";
    foreach ($rows as $row) {
        $userId   = htmlspecialchars($row["user_id"],   ENT_QUOTES, "UTF-8");
        $username = htmlspecialchars($row["username"],  ENT_QUOTES, "UTF-8");
        $fullname = htmlspecialchars($row["fullname"],  ENT_QUOTES, "UTF-8");
        $roleId   = htmlspecialchars($row["role_id"],   ENT_QUOTES, "UTF-8");
        $checked  = $row["is_active"] ? "checked" : "";
        $date     = htmlspecialchars($row["created_at"], ENT_QUOTES, "UTF-8");

        $html .= "
        <tr data-id='{$userId}'>
            <td>{$userId}</td>
            <td>{$username}</td>
            <td>{$fullname}</td>
            <td>{$roleId}</td>
            <td class='text-center'>
                <input class='form-check-input toggle-user-status' type='checkbox' data-id='{$userId}' {$checked}>
            </td>
            <td>{$date}</td>
            <td>
                <button class='btn btn-sm btn-outline-primary btn-edit-user' data-id='{$userId}'>
                    <i class='bi bi-pencil'></i> Edit
                </button>
                <button class='btn btn-sm btn-outline-danger btn-delete-user' data-id='{$userId}'>
                    <i class='bi bi-trash'></i> Delete
                </button>
            </td>
        </tr>";
    }

    echo json_encode(["html" => $html]); exit;
}

function createUser(PDO $pdo): void
{
    $username = trim($_POST["username"]  ?? "");
    $fullname = trim($_POST["fullname"]  ?? "");
    $roleId = (int) ($_POST["role_id"] ?? 0);
    $password = $_POST["password"] ?? "";

    if (!$username || !$fullname || !$roleId || !$password) {
        echo json_encode(["error" => "All fields are required."]); exit;
    }

    $stmt = $pdo->prepare(
        "INSERT INTO user_management (username, fullname, role_id, password_hash)
         VALUES (:username, :fullname, :role_id, :password_hash)"
    );
    $stmt->execute([
        ":username"      => $username,
        ":fullname"      => $fullname,
        ":role_id"       => $roleId,
        ":password_hash" => password_hash($password, PASSWORD_DEFAULT),
    ]);

    echo json_encode(["success" => true, "user_id" => (int) $pdo->lastInsertId()]); exit;
}

function updateUser(PDO $pdo): void
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

function toggleUserStatus(PDO $pdo): void
{
    $userId   = (int) ($_POST["user_id"]   ?? 0);
    $isActive = (int) ($_POST["is_active"] ?? 0); // 0 or 1 from JS

    if (!$userId) {
        echo json_encode(["error" => "Missing user ID."]); exit;
    }

    $stmt = $pdo->prepare(
        "UPDATE user_management
         SET    is_active = :is_active
         WHERE  user_id   = :user_id"
    );
    $stmt->execute([
        ":is_active" => $isActive,
        ":user_id"   => $userId,
    ]);

    echo json_encode(["success" => true]); exit;
}

function deleteUser(PDO $pdo): void
{
    $userId = (int) ($_POST["user_id"] ?? 0);

    if (!$userId) {
        echo json_encode(["error" => "Missing user ID."]); exit;
    }

    $stmt = $pdo->prepare("DELETE FROM user_management WHERE user_id = :user_id");
    $stmt->execute([":user_id" => $userId]);

    echo json_encode(["success" => true]); exit;
}

// ── Dispatch ──────────────────────────────────────────────────────────────────

$request = trim($_POST["request"] ?? "");

switch ($request) {
    case "getUserTable":     UserTable($pdo);     break;
    case "createUser": createUser($pdo);       break;
    case "updateUser": updateUser($pdo);       break;
    case "toggleUserStatus": toggleUserStatus($pdo); break;
    case "deleteUser": deleteUser($pdo);       break;
    default: echo json_encode(["error" => "Unknown request: '{$request}'"]);
}