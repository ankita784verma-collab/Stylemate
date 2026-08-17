<?php
$host = getenv("DB_HOST") ?: "localhost";
$username = getenv("DB_USER") ?: "root";
$password = getenv("DB_PASSWORD") !== false ? getenv("DB_PASSWORD") : "";
$database = getenv("DB_NAME") ?: "stylemate_db";
$port = getenv("DB_PORT") ?: 3306;

$conn = new mysqli($host, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>