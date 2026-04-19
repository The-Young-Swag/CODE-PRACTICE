<?php
session_start(); // always first, before any output
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method not allowed"]);
    exit;
}

$existingAcc = [
    "acc1" => [
        "username" => "the_young_swag",
        "password" => "011603"
    ],
    "acc2" => [
        "username" => "xplizitNM7",
        "password" => "011603"
    ]
];


function validateUser($existingAcc, $username, $password) {
    foreach($existingAcc as $account){
        if($account["username"] === $username){
            if ($account["password"] === $password){
                return true;
            }
        }
    }
    return false;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$isValid = validateUser($existingAcc,$username, $password);

if ($isValid) {
    $_SESSION['username'] = $username; // only set session AFTER validation passes
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}