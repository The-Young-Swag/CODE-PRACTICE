<?php
// ... (variables from step 1)
require_once __DIR__ .  "/../db/db.php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception for consistent error handling in subsequent operations
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully";

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    // Optionally, terminate the script if the connection is critical
    // die(); 
}

?>