<?php
// Backend Router - All requests go through this file
header('Content-Type: application/json');

session_start();
require_once 'config/db.php';

$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base_path = '/backend';
$route = str_replace($base_path, '', $request_uri);

// Route mapping
$routes = [
    // Auth routes
    'POST /api/auth/login' => 'api/auth/login.php',
    'POST /api/auth/register' => 'api/auth/register.php',
    'POST /api/auth/logout' => 'api/auth/logout.php',
    
    // Clothing routes
    'POST /api/clothing/add' => 'api/clothing/add.php',
    'GET /api/clothing/list' => 'api/clothing/list.php',
    'DELETE /api/clothing/delete' => 'api/clothing/delete.php',
    
    // Outfit routes
    'POST /api/outfit/generate' => 'api/outfit/generate.php',
];

$method = $_SERVER['REQUEST_METHOD'];
$route_key = "$method $route";

if (isset($routes[$route_key])) {
    require_once $routes[$route_key];
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Route not found']);
}
?>
