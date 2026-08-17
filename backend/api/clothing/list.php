<?php
// Clothing API - List Items
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT ci.*, c.name as category_name
    FROM clothing_items ci
    INNER JOIN categories c ON ci.category_id = c.id
    WHERE ci.user_id = ?
    ORDER BY ci.created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode(['success' => true, 'items' => $items]);
?>
