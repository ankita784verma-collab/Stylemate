<?php
// Auth API - Logout
session_start();

session_unset();
session_destroy();

echo json_encode(['success' => true, 'message' => 'Logged out successfully']);
?>
