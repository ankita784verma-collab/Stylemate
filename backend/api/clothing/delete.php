<?php
// Clothing API - Delete Item
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] != 'DELETE') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$user_id = $_SESSION['user_id'];
$item_id = $_GET['id'] ?? 0;

if ($item_id <= 0) {
    exit(json_encode(['error' => 'Item ID required']));
}

$stmt = $conn->prepare("SELECT image FROM clothing_items WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $item_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    http_response_code(404);
    exit(json_encode(['error' => 'Item not found']));
}

$item = $result->fetch_assoc();
$image = $item['image'];

$delete = $conn->prepare("DELETE FROM clothing_items WHERE id = ? AND user_id = ?");
$delete->bind_param("ii", $item_id, $user_id);
$delete->execute();

if ($delete->affected_rows > 0 && file_exists($image)) {
    unlink($image);
}

echo json_encode(['success' => true, 'message' => 'Item deleted']);
?>
