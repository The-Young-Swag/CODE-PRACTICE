<?php
require_once "dbConnect.php";



function loadTask(){
    global $pdo;
        // 1. write your SQL question
    $sql = "SELECT * FROM listContent";

    // 2. prepare it — PDO checks it's valid SQL
    $stmt = $pdo->prepare($sql);

    // 3. execute it — actually runs the query
    $stmt->execute();

    // 4. fetch all results as an array
    $tasks = $stmt->fetchAll();
    
    return $tasks;

}

echo json_encode(loadTask());