<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }
header('Content-Type: application/json');

// LOCAL DEV STUB — returns empty test_categories so frontend falls back to lookup data
// The phoenix_lookup_controller.php is the source of truth for this local session
echo json_encode([
    "test_categories" => []
]);
