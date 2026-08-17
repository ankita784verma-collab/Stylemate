<?php
// Outfit API - Generate Outfit
session_start();
require_once '../../config/db.php';

// Get API key from .env
$gemini_api_key = getenv('GEMINI_API_KEY');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit(json_encode(['error' => 'Unauthorized']));
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    http_response_code(405);
    exit(json_encode(['error' => 'Method not allowed']));
}

$user_id = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

$occasion = $input['occasion'] ?? '';
$season = $input['season'] ?? '';
$style_pref = $input['style'] ?? '';

// Fetch user's wardrobe
$stmt = $conn->prepare("
    SELECT * FROM clothing_items 
    WHERE user_id = ? 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Generate outfit using Gemini API (placeholder)
$outfit = [
    'items' => array_slice($items, 0, 3),
    'suggestion' => 'Outfit generated based on your wardrobe',
    'occasion' => $occasion,
    'season' => $season
];

echo json_encode(['success' => true, 'outfit' => $outfit]);
?>
