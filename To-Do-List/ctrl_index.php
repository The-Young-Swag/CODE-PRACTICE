<?php
require_once "dbConnect.php";

header("Content-Type: application/json");

//  ROUTING 
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    echo json_encode(loadTask());
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    createTask($_POST["task"] ?? '');
    echo json_encode(["status" => "success"]);
}

//  LOAD
function loadTask(){
    global $pdo;

    $sql = "SELECT * FROM listContent";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//  CREATE
function createTask($task){
    global $pdo;

    $sql = "INSERT INTO listContent (listContent) VALUES (?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$task]);
}