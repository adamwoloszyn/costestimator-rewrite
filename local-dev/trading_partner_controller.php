<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: X-CSRF-Token, Accept, Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }
header('Content-Type: application/json');

$partners = [
    ["id" => "1",  "tp_id" => "480", "phxPayerId" => "", "payerName" => "", "displayName" => "I don't have insurance",  "tradingParterId" => "", "coverage_twins" => "", "coverage_triplets" => "", "coverage_four_or_more" => "", "average_risk_coverage" => ""],
    ["id" => "2",  "tp_id" => "480", "phxPayerId" => "", "payerName" => "", "displayName" => "I don't see my insurance", "tradingParterId" => "", "coverage_twins" => "", "coverage_triplets" => "", "coverage_four_or_more" => "", "average_risk_coverage" => ""],
    ["id" => "10", "tp_id" => "10",  "phxPayerId" => "BCBS", "payerName" => "BCBS", "displayName" => "Blue Cross Blue Shield", "tradingParterId" => "BCBS001", "coverage_twins" => "Y", "coverage_triplets" => "N", "coverage_four_or_more" => "N", "average_risk_coverage" => "Y"],
    ["id" => "11", "tp_id" => "11",  "phxPayerId" => "AETNA", "payerName" => "AETNA", "displayName" => "Aetna", "tradingParterId" => "AETNA001", "coverage_twins" => "Y", "coverage_triplets" => "N", "coverage_four_or_more" => "N", "average_risk_coverage" => "Y"],
    ["id" => "12", "tp_id" => "12",  "phxPayerId" => "CIGNA", "payerName" => "CIGNA", "displayName" => "Cigna", "tradingParterId" => "CIGNA001", "coverage_twins" => "Y", "coverage_triplets" => "N", "coverage_four_or_more" => "N", "average_risk_coverage" => "Y"],
    ["id" => "13", "tp_id" => "13",  "phxPayerId" => "UHC", "payerName" => "UHC", "displayName" => "United Healthcare", "tradingParterId" => "UHC001", "coverage_twins" => "Y", "coverage_triplets" => "N", "coverage_four_or_more" => "N", "average_risk_coverage" => "Y"],
    ["id" => "14", "tp_id" => "14",  "phxPayerId" => "HUMANA", "payerName" => "HUMANA", "displayName" => "Humana", "tradingParterId" => "HUMANA001", "coverage_twins" => "Y", "coverage_triplets" => "N", "coverage_four_or_more" => "N", "average_risk_coverage" => "Y"],
];

echo json_encode($partners);
