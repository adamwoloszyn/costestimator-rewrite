<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }
header('Content-Type: application/json');

// Always return a valid/found result so the form can proceed in local dev
echo json_encode([
    "phone_lookup" => [
        "http_code" => 200,
        "results" => [
            "phoneNumber" => $_GET['phoneNumber'] ?? "",
            "valid" => true
        ]
    ]
]);
