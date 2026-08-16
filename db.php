<<<<<<< HEAD
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "stylemate_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

=======
<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "stylemate_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

>>>>>>> 44c54392920554fc489fa0b5fa2377643cfac17d
?>