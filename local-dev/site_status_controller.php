<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit(); }
header('Content-Type: application/json');

// LOCAL DEV STUB — site is active, no maintenance
echo json_encode([
    "site_status" => [
        "http_code" => 200,
        "status" => [
            "results" => [
                "ASAP" => "active",
                "Estimator" => "active"
            ]
        ]
    ],
    "token" => "local-dev-token",
    "id" => "local-dev-session"
]);
