<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ajax";

try {
  $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}
return $pdo;  // ← THIS is what was missing
?>