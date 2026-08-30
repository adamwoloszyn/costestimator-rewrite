<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }
header('Content-Type: application/json');

// Mock estimate response — mirrors the shape of the real cost_estimate_controller
$mockId = 'LOCAL-' . strtoupper(substr(md5(uniqid()), 0, 8));

echo json_encode([
    "estimateID" => $mockId,
    "FrontEndMessage" => [
        "response" => [
            "response_for_front_end"     => "Your estimated out-of-pocket is",
            "response_for_front_end_two" => "This is a local dev mock estimate. No real data was submitted.",
            "additional_actions"         => "",
            "additional_actions_message" => "Based on the information you provided, this is a mock estimate for local development.",
            "cost_estimate_id_message"   => "Your cost estimate number is <b>{$mockId}</b>. (Local dev mock)"
        ]
    ],
    "CostEstimateCurrency" => "USD",
    "CostEstimate"         => "0.00",
    "local_dev_mock"       => true
]);
