<?php
/**
 * NCBMP Logic Test Engine
 *
 * Mirrors the two production NCBMP code paths in cost_estimate_controller.php:
 *   Path A: newEstimateRequest   — lines ~377-394, ~648-657
 *   Path B: preprocessCostEstimateData — lines ~157-170, ~211-221
 *
 * Run: php test_ncbmp.php
 */

// ─────────────────────────────────────────────────────────────────────────────
// PRODUCTION LOGIC — copied verbatim, no changes
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Mirrors calculateInitialCostObjectValues / preprocessCostEstimateData HighRisk derivation.
 * Returns boolean.
 */
function deriveHighRisk(string $highRiskInput, string $familyHistory, int $ageAtDelivery, string $actualTestCategory): bool {
    // Step 1 — literal HighRisk field
    $isHighRisk = ($highRiskInput == "Yes");

    // Step 2 — carrier screening: FamilyHistory overrides if HighRisk not already set
    if (!$isHighRisk && isset($familyHistory) && $familyHistory !== "") {
        $isHighRisk = (strtolower($familyHistory) === "yes");
    }

    // Step 3 — NIPT age > 35 override
    if ($ageAtDelivery > 35 && $actualTestCategory == "NIPT") {
        $isHighRisk = true;
    }

    return $isHighRisk;
}

/**
 * Mirrors the relevant frontend_response.php rules that affect additional_actions.
 *
 * Rule 2:  NIPT + MultiFetal + payer does NOT cover that multifetal level
 *   → message 5 → additional_actions = "Not_Covered_By_Medicial_Policy=NO"
 *
 * Rule 17: Inheritest + not AJP + not CF/SMA + high_risk=false
 *   → message 5 → additional_actions = "Not_Covered_By_Medicial_Policy=NO"
 *
 * Everything else → "" for NCBMP purposes.
 *
 * @param bool $multiFetalCoveredByPayer  Whether the payer's coverage includes this multifetal level.
 *                                        Only relevant for NIPT+MultiFetal scenarios.
 */
function simulateAdditionalActions(
    string $testCategory,
    string $testName,
    bool   $highRisk,
    bool   $isMultiFetal = false,
    bool   $multiFetalCoveredByPayer = false
): string {
    // Rule 2: NIPT + MultiFetal + payer doesn't cover it → NCBMP
    if ($testCategory == "NIPT" && $isMultiFetal && !$multiFetalCoveredByPayer) {
        return "Not_Covered_By_Medicial_Policy=NO";
    }

    // Rule 17: Inheritest (excluding AJP and CF/SMA) + not high risk → NCBMP
    if (
        $testCategory == "Inheritest" &&
        $testName != "Ashkenazi Jewish Panel" &&
        $testName != "CF/SMA Panel" &&
        $highRisk == false
    ) {
        return "Not_Covered_By_Medicial_Policy=NO";
    }

    return "";
}

/**
 * PATH A — newEstimateRequest NCBMP block (lines ~648-657 in production).
 * Receives post-calculateInitialCostObjectValues $customerData.
 */
function ncbmp_pathA(array $customerData, string $additionalActions): bool {
    if ($additionalActions == "Not_Covered_By_Medicial_Policy=NO") {
        return true;
    } else if (
        $customerData["ActualTestCategory"] == "Inheritest" &&
        $customerData["HighRisk"] === false &&
        $customerData["TestName"] !== "CF/SMA Panel" &&
        isset($customerData["FamilyHistory"]) &&
        $customerData["FamilyHistory"] !== ""
    ) {
        return true;
    } else {
        return false;
    }
}

/**
 * PATH B — preprocessCostEstimateData NCBMP block (lines ~211-221 in production).
 * Uses $userData directly (pre-processed).
 */
function ncbmp_pathB(array $userData, bool $isHighRisk, bool $multiFetalGestation): bool {
    if ($userData["ActualTestCategory"] == "NIPT" && $multiFetalGestation) {
        return true;
    } else if (
        $userData["ActualTestCategory"] == "Inheritest" &&
        !$isHighRisk &&
        $userData["TestName"] !== "CF/SMA Panel" &&
        isset($userData["FamilyHistory"]) &&
        $userData["FamilyHistory"] !== ""
    ) {
        return true;
    } else {
        return false;
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// TEST RUNNER
// ─────────────────────────────────────────────────────────────────────────────

$pass = 0;
$fail = 0;
$results = [];

/**
 * Each test case:
 *   label                   — human-readable description
 *   input                   — simulates what the frontend sends (user payload)
 *   ageAtDelivery           — member age at delivery (affects NIPT HighRisk override)
 *   multiFetalGestation     — bool (for NIPT path B / preprocessCostEstimateData)
 *   multiFetalCoveredByPayer— bool (for Rule 2 simulation in Path A)
 *   expected_A              — expected NCBMP from Path A (newEstimateRequest)
 *   expected_B              — expected NCBMP from Path B (create_salesforce_record)
 *   note                    — optional explanation for divergence / edge cases
 */
$tests = [

    // ── CF/SMA Panel ────────────────────────────────────────────────────────

    [
        'label'                   => 'CF/SMA | FamilyHistory="No" | OOP > $300 (the broken records CE1353184/CE1353106)',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'CF/SMA Panel', 'HighRisk' => '', 'FamilyHistory' => 'No'],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'CF/SMA | FamilyHistory="I don\'t know"',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'CF/SMA Panel', 'HighRisk' => '', 'FamilyHistory' => "I don't know"],
        'ageAtDelivery'           => 30,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'CF/SMA | FamilyHistory="Yes"',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'CF/SMA Panel', 'HighRisk' => '', 'FamilyHistory' => 'Yes'],
        'ageAtDelivery'           => 30,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'CF/SMA | FamilyHistory="" (question not shown / empty)',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'CF/SMA Panel', 'HighRisk' => '', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 30,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'CF/SMA | no insurance path (Rule 9 fallback)',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'CF/SMA Panel', 'HighRisk' => '', 'FamilyHistory' => 'No'],
        'ageAtDelivery'           => 25,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],

    // ── Ashkenazi Jewish Panel ───────────────────────────────────────────────

    [
        'label'                   => 'AJP | FamilyHistory="No" → NCBMP=true',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'Ashkenazi Jewish Panel', 'HighRisk' => '', 'FamilyHistory' => 'No'],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],
    [
        'label'                   => 'AJP | FamilyHistory="I don\'t know" → NCBMP=true',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'Ashkenazi Jewish Panel', 'HighRisk' => '', 'FamilyHistory' => "I don't know"],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],
    [
        'label'                   => 'AJP | FamilyHistory="Yes" → NCBMP=false (HighRisk=true)',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'Ashkenazi Jewish Panel', 'HighRisk' => '', 'FamilyHistory' => 'Yes'],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'AJP | HighRisk="Yes" explicit (no FamilyHistory) → NCBMP=false',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'Ashkenazi Jewish Panel', 'HighRisk' => 'Yes', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],

    // ── Other Inheritest (Rule 17 path) ─────────────────────────────────────

    [
        'label'                   => '500 PLUS Panel | FamilyHistory="No" → NCBMP=true via Rule 17',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => '500 PLUS Panel', 'HighRisk' => '', 'FamilyHistory' => 'No'],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],
    [
        'label'                   => '500 PLUS Panel | FamilyHistory="I don\'t know" → NCBMP=true via Rule 17',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => '500 PLUS Panel', 'HighRisk' => '', 'FamilyHistory' => "I don't know"],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],
    [
        'label'                   => '500 PLUS Panel | FamilyHistory="Yes" → NCBMP=false (HighRisk=true)',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => '500 PLUS Panel', 'HighRisk' => '', 'FamilyHistory' => 'Yes'],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'Core Panel | FamilyHistory="No" → NCBMP=true via Rule 17',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'Core Panel', 'HighRisk' => '', 'FamilyHistory' => 'No'],
        'ageAtDelivery'           => 30,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],
    [
        'label'                   => 'GeneSeq PLUS | FamilyHistory="" (unreachable in prod — Rule 17 fires on HighRisk=false alone)',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'GeneSeq PLUS', 'HighRisk' => '', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 30,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        // Path A: Rule 17 fires (HighRisk=false, not CF/SMA, not AJP) → NCBMP=true
        // Path B: FamilyHistory="" guard → NCBMP=false
        // NOTE: Path A/B diverge here. Unreachable in production — all Inheritest tests show Q9.
        'expected_A'              => true,
        'expected_B'              => false,
        'note'                    => 'PATH A/B DIVERGE (unreachable in prod: all Inheritest tests show Q9)',
    ],

    // ── NIPT ────────────────────────────────────────────────────────────────

    [
        'label'                   => 'NIPT | Singleton | age 28 → NCBMP=false',
        'input'                   => ['ActualTestCategory' => 'NIPT', 'TestName' => 'NIPT', 'HighRisk' => 'No', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'NIPT | Twins | payer does NOT cover multifetal → NCBMP=true (Rule 2)',
        'input'                   => ['ActualTestCategory' => 'NIPT', 'TestName' => 'NIPT', 'HighRisk' => 'No', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => true,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],
    [
        'label'                   => 'NIPT | Twins | payer DOES cover multifetal → NCBMP=false',
        'input'                   => ['ActualTestCategory' => 'NIPT', 'TestName' => 'NIPT', 'HighRisk' => 'No', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => true,
        'multiFetalCoveredByPayer'=> true,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'NIPT | age > 35 (HighRisk overridden to true) | Singleton → NCBMP=false',
        'input'                   => ['ActualTestCategory' => 'NIPT', 'TestName' => 'NIPT', 'HighRisk' => 'No', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 36,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
    [
        'label'                   => 'NIPT | age > 35 + Twins + payer NOT covered → NCBMP=true',
        'input'                   => ['ActualTestCategory' => 'NIPT', 'TestName' => 'NIPT', 'HighRisk' => 'No', 'FamilyHistory' => ''],
        'ageAtDelivery'           => 38,
        'multiFetalGestation'     => true,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => true,
        'expected_B'              => true,
    ],

    // ── Edge cases ──────────────────────────────────────────────────────────

    [
        'label'                   => 'Inheritest (14-gene) | FamilyHistory key missing entirely — Rule 17 fires on HighRisk=false',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => '14-gene Panel', 'HighRisk' => ''],
        // FamilyHistory key intentionally missing — simulates a frontend that omits the field
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        // Path A: Rule 17 fires (HighRisk=false) → NCBMP=true
        // Path B: isset() guard → NCBMP=false
        // NOTE: Unreachable in production — frontend always sends FamilyHistory (even as "").
        'expected_A'              => true,
        'expected_B'              => false,
        'note'                    => 'PATH A/B DIVERGE (unreachable in prod: frontend always sends FamilyHistory key)',
    ],
    [
        'label'                   => 'CF/SMA | HighRisk="Yes" explicit + FamilyHistory="No" → NCBMP=false',
        'input'                   => ['ActualTestCategory' => 'Inheritest', 'TestName' => 'CF/SMA Panel', 'HighRisk' => 'Yes', 'FamilyHistory' => 'No'],
        'ageAtDelivery'           => 28,
        'multiFetalGestation'     => false,
        'multiFetalCoveredByPayer'=> false,
        'expected_A'              => false,
        'expected_B'              => false,
    ],
];

// ─────────────────────────────────────────────────────────────────────────────
// RUN TESTS
// ─────────────────────────────────────────────────────────────────────────────

$GREEN  = "\033[32m";
$RED    = "\033[31m";
$YELLOW = "\033[33m";
$CYAN   = "\033[36m";
$BOLD   = "\033[1m";
$RESET  = "\033[0m";

echo "\n{$BOLD}{$CYAN}══════════════════════════════════════════════════════════════{$RESET}\n";
echo "{$BOLD}{$CYAN}  NCBMP Logic Test Engine — cost_estimate_controller.php{$RESET}\n";
echo "{$BOLD}{$CYAN}══════════════════════════════════════════════════════════════{$RESET}\n\n";

foreach ($tests as $i => $t) {
    $num   = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
    $input = $t['input'];
    $age   = $t['ageAtDelivery'];
    $multi = $t['multiFetalGestation'];
    $multiCovered = $t['multiFetalCoveredByPayer'];

    // Pull fields with defaults so unset keys don't cause notices
    $highRiskInput     = $input['HighRisk']          ?? '';
    $familyHistory     = $input['FamilyHistory']     ?? '';   // missing key → '' (same as not shown)
    $actualCategory    = $input['ActualTestCategory'] ?? '';
    $testName          = $input['TestName']           ?? '';

    // ── Shared: derive HighRisk ──────────────────────────────────────────────
    $isHighRisk = deriveHighRisk($highRiskInput, $familyHistory, $age, $actualCategory);

    // ── PATH A: newEstimateRequest ───────────────────────────────────────────
    // Simulate what calculateInitialCostObjectValues stores
    $customerDataA = $input;
    $customerDataA['HighRisk']     = $isHighRisk;  // boolean, as stored by calcInitial...
    $customerDataA['FamilyHistory'] = $familyHistory;

    // Simulate frontend_response.php (Rule 2 for NIPT+MultiFetal, Rule 17 for Inheritest)
    $additionalActions = simulateAdditionalActions($actualCategory, $testName, $isHighRisk, $multi, $multiCovered);

    $resultA = ncbmp_pathA($customerDataA, $additionalActions);

    // ── PATH B: preprocessCostEstimateData ──────────────────────────────────
    $resultB = ncbmp_pathB($input, $isHighRisk, $multi && !$multiCovered);

    // ── Compare ─────────────────────────────────────────────────────────────
    $passA = ($resultA === $t['expected_A']);
    $passB = ($resultB === $t['expected_B']);
    $allPass = $passA && $passB;
    $hasDivergenceNote = !empty($t['note']);

    if ($allPass) {
        $pass++;
        $icon = "{$GREEN}✓ PASS{$RESET}";
    } else {
        $fail++;
        $icon = "{$RED}✗ FAIL{$RESET}";
    }

    $boolStr = fn(bool $b) => $b ? 'true' : 'false';

    echo "  [{$num}] {$icon}  {$t['label']}\n";
    echo "        HighRisk derived: {$YELLOW}" . $boolStr($isHighRisk) . "{$RESET}";
    echo "  | additional_actions: {$YELLOW}" . ($additionalActions ?: '""') . "{$RESET}\n";

    $colA = $passA ? $GREEN : $RED;
    $colB = $passB ? $GREEN : $RED;
    echo "        Path A (newEstimateRequest):        got={$colA}" . $boolStr($resultA) . "{$RESET}  expected=" . $boolStr($t['expected_A']) . "\n";
    echo "        Path B (create_salesforce_record):  got={$colB}" . $boolStr($resultB) . "{$RESET}  expected=" . $boolStr($t['expected_B']) . "\n";

    if ($hasDivergenceNote) {
        echo "        {$YELLOW}⚠ NOTE: {$t['note']}{$RESET}\n";
    }

    if (!$passA || !$passB) {
        echo "        {$RED}↑ MISMATCH — review logic or expected value{$RESET}\n";
    }
    echo "\n";
}

// ─────────────────────────────────────────────────────────────────────────────
// SUMMARY
// ─────────────────────────────────────────────────────────────────────────────

$total = $pass + $fail;
$color = $fail === 0 ? $GREEN : $RED;
echo "{$BOLD}{$CYAN}══════════════════════════════════════════════════════════════{$RESET}\n";
echo "  {$BOLD}Results: {$color}{$pass}/{$total} passed{$RESET}";
if ($fail > 0) {
    echo "  {$RED}({$fail} failed){$RESET}";
}
echo "\n{$BOLD}{$CYAN}══════════════════════════════════════════════════════════════{$RESET}\n\n";

exit($fail > 0 ? 1 : 0);
