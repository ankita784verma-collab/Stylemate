<?php
// Clothing API - Add Item
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$user_id = $_SESSION['user_id'];
$name = trim($_POST['name'] ?? '');
$category_id = intval($_POST['category_id'] ?? 0);
$color = trim($_POST['color'] ?? '');
$secondary_color = trim($_POST['secondary_color'] ?? '');
$pattern = trim($_POST['pattern'] ?? '');
$style = trim($_POST['style'] ?? '');
$season = trim($_POST['season'] ?? '');
$occasion = trim($_POST['occasion'] ?? '');

if (empty($name) || $category_id <= 0) {
    exit(json_encode(['error' => 'Name and category required']));
}

if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
    exit(json_encode(['error' => 'Image upload required']));
}

$file = $_FILES['image'];
$allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
$max_size = 5 * 1024 * 1024;

if (!in_array($file['type'], $allowed_types)) {
    exit(json_encode(['error' => 'Only JPG, PNG, WEBP allowed']));
}

if ($file['size'] > $max_size) {
    exit(json_encode(['error' => 'File too large (max 5MB)']));
}

$image_info = getimagesize($file['tmp_name']);
if ($image_info === false) {
    exit(json_encode(['error' => 'Invalid image']));
}

$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$new_filename = uniqid('clothing_', true) . '.' . $extension;
$upload_directory = '../../uploads/';
$image_path = $upload_directory . $new_filename;

if (!move_uploaded_file($file['tmp_name'], $image_path)) {
    exit(json_encode(['error' => 'Upload failed']));
}

$stmt = $conn->prepare("INSERT INTO clothing_items (user_id, category_id, name, image, color, secondary_color, pattern, style, season, occasion) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("iissssssss", $user_id, $category_id, $name, $image_path, $color, $secondary_color, $pattern, $style, $season, $occasion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Item added']);
} else {
    if (file_exists($image_path)) unlink($image_path);
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save item']);
}
?>
