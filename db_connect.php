<?php

ini_set("UPLOAD_MAX_FILESIZE", "500M");
ini_set("POST_MAX_SIZE", "500M");
ini_set("MAX_EXECUTION_TIME", "300");
ini_set("MEMORY_LIMIT", "1G");

$host = "localhost";
$dbname = "testing";
$username = "root";
$password = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>
