<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}
header('Content-Type: application/json');

// LOCAL DEV: serve modified static JSON instead of calling Genetic Estimator REST
$json = file_get_contents(__DIR__ . '/static_lookup.json');
echo $json;
