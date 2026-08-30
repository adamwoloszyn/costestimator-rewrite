# PEP Phase 5 — Requirements Analysis
**Date:** July 5, 2026
**Project:** Cost Estimator Rewrite

---

## Architecture Reference

Before reviewing estimates, note the confirmed data architecture:

- **Test display names, descriptions, CPT codes, test names** → DB `tests` table, served by `tests_controller.php`
- **`StaticTestCategories.js`** → Dead code. Not imported anywhere. Ignore.
- **"I am not sure" options** → Also rows in the `tests` DB table
- **Category display names** → `test_categories` DB table
- **Category internal codes** ("NIPT", "Inheritest") → Hardcoded in `tests_controller.php` and referenced in `frontend_response.php` routing rules
- **`frontend_response.php` hardcoded strings** → `"Ashkenazi Jewish Panel"`, `"CF/SMA Panel"`, `"NIPT"`, `"Inheritest"`

---

## Requirements Table

| PEP # | Description | Responsible Party | Questions / Blockers | AW Hours |
|-------|-------------|-------------------|----------------------|----------|
| PEP-001 | Add Labcorp 790 PLUS Panel test | AW (DB insert to `tests` table). Phoenix/Genetic Estimator team (confirm `index_in_phoenix` + primary LCA code). Salesforce team. | AW blocked on: (1) primary LCA CPT code from Phoenix team, (2) `index_in_phoenix` integer for this test, (3) confirm `display_name` / `header_display` strings per PEP spec. DB record only — no frontend code change. | 2h |
| PEP-002 | Update Ashkenazi Jewish question text in Phoenix endpoint | Genetic Estimator team (their REST update). AW (verify + test). | **Critical for AW:** Does the Phoenix question ID stay the same? If question ID 9 changes to a new ID, `FamilyHistory_9` field references in `cost_estimate_controller.php` and `salesforce_controller.php` all need updating. AW cannot begin until question ID is confirmed stable. | 1h |
| PEP-003 | Update/remove Inheritest NCBMP logic tied to Ashkenazi Jewish question | AW (`cost_estimate_controller.php` — update NCBMP condition block). | **Deployment dependency:** Must go live simultaneously with PEP-002 Phoenix change. Cannot deploy before new question is active. AW: What is the new NCBMP rule for AJP with the new question — is NCBMP=true only for Yes, or always? | 2.5h |
| PEP-004 | Rename 500 PLUS Panel | AW (DB `tests` table: update `display_name`, `header_display`, `test_description`, `test_name` columns). | What are the new display name and description strings? Need exact strings from PEP spec before DB update. | 0.5h |
| PEP-005 | Rename 300 PLUS Panel | AW (DB `tests` table update, same columns). | Same questions as PEP-004. | 0.5h |
| PEP-006 | Rename 100 PLUS Panel | AW (DB `tests` table update). | Same as PEP-004. | 0.5h |
| PEP-007 | Rename High Frequency Panel | AW (DB `tests` table update). | Same as PEP-004. | 0.5h |
| PEP-008 | Rename 14-gene Panel | AW (DB `tests` table update). | Same as PEP-004. | 0.5h |
| PEP-009 | Update Ashkenazi Jewish Panel NCBMP/HighRisk routing logic | AW (`cost_estimate_controller.php` + `frontend_response.php` — AJP-specific rule). | What is the new expected logic? Is AJP treated the same as other Inheritest (FamilyHistory Yes = HighRisk, No/IDK = NCBMP)? Or does the new AJP question have a different ID than 9? This is a logic change, not just a rename. | 3.5h |
| PEP-010 | Rename Core Panel | AW (DB `tests` table update). | Same as PEP-004. | 0.5h |
| PEP-011 | Rename CF/SMA Panel | AW (DB `tests` table update **+** `frontend_response.php` hardcoded string update). | **Important:** "CF/SMA Panel" is hardcoded in `frontend_response.php` rules 12, 14, and 17. If `test_name` changes, those rule conditions must be updated simultaneously or routing breaks. What is the exact new `test_name` value? | 1.5h |
| PEP-012 | Rename GeneSeq PLUS | AW (DB `tests` table update). | Same as PEP-004. | 0.5h |
| PEP-013 | "I am not sure" carrier screening option → point to High Frequency Carrier Panel (currently points to 14-gene) | AW (DB `tests` table — update `test_name`, `cpt_bundle`, and `index_in_phoenix` columns for the "Inheritest - Not Sure" record). | What is the High Frequency Carrier Panel's `cpt_bundle` code and `index_in_phoenix` value? Both the Pre-Pregnancy and Pregnancy instances update automatically from one DB record. | 1h |
| PEP-014 | Add Fetal RhD test (LCA 452499) to Pregnancy section | AW (DB `tests` table insert). Phoenix/Genetic Estimator team (confirm `index_in_phoenix` for 452499). | What category string should Fetal RhD use — "NIPT" or a new category string? What `ordernum` for placement? What `display_name`, `header_display`, `test_description`? | 1.5h |
| PEP-015 | Add "Are you Rh negative?" question for Fetal RhD test | AW (`InsuranceInfo.jsx` — add question ID rendering + `cost_estimate_controller.php` — handle new question answer). Genetic Estimator team (add question to their endpoint for Fetal RhD test ID). | AW needs: (1) question ID from Genetic Estimator team, (2) answer option IDs (yes/no). Must be implemented with PEP-031 routing rules. New question answer field will need a state key (e.g., `RhNegative_[id]`). | 5h |
| PEP-016 | Add new "Prenatal Diagnostics and Chromosome Analysis" test category/section | AW (DB `test_categories` table insert + DB `tests` inserts for the new section's tests). | What internal category code string? This string will be hardcoded in `tests_controller.php` alongside "NIPT" and "Inheritest" — must decide on the internal key before any code work. What tests go in this section? What image/color? | 3h |
| PEP-017 | Add new test (no cost offered, no consent-to-questionnaire) | AW (DB `tests` insert + `frontend_response.php` rule). Salesforce team. Phoenix team (primary LCA code). | What LCA code? What is "no cost offered" behavior — is this a `frontend_response.php` routing rule change or a Salesforce-side flag only? Does the frontend hide the consent questions for this test? If yes, which question IDs need to be skipped? | 2.5h |
| PEP-018 | Add test 511997 with gestational age + recurrent miscarriage conditional questions | AW (`InsuranceInfo.jsx` new question rendering + `cost_estimate_controller.php` new logic + `frontend_response.php` new rule + DB `tests` insert). Genetic Estimator team (question config). | Most complex story. AW needs: (1) question IDs from Genetic Estimator REST for gestational age and recurrent miscarriage questions, (2) answer option IDs, (3) routing logic — what message does each answer combination trigger?, (4) `index_in_phoenix` for 511997. | 10h |
| PEP-019 | Add test 510110 (similar conditional logic to PEP-018) | AW (reuse PEP-018 frontend/backend logic + DB `tests` insert). | Do the same question IDs apply to 510110 as 511997? If so, most of PEP-018 code can be reused. What `index_in_phoenix` for 510110? | 3h |
| PEP-020 | Add test 510200/510100 (no special question logic) | AW (DB `tests` insert + routing rule if needed). Phoenix team (primary LCA code). | What are the CPT codes and `index_in_phoenix` for 510200/510100? Any routing rule needed or does it fall through to default? | 2h |
| PEP-021 | Validate all test renames across Genetic Estimator REST tables and our system | AW (review `frontend_response.php` hardcoded strings post-rename; confirm no stale name references). Genetic Estimator team (their REST updates). Salesforce team. | AW action: After PEP-004 through PEP-012 DB changes, scan `frontend_response.php` for any hardcoded test name comparisons using old names. Currently only "CF/SMA Panel" and "Ashkenazi Jewish Panel" are hardcoded — confirm those are the only two. | 1h |
| PEP-022 | Salesforce product display name string updates | Salesforce team (Brian). | AW: No code action. Notify AW when final product display strings are confirmed so we can verify they match the `tests` DB table `display_name` column exactly. | 0h |
| PEP-023 | Genetic Estimator REST table alignment meeting | Genetic Estimator team (primary). AW (attend + follow-up). | AW should attend to confirm: (1) `index_in_phoenix` values for all new tests, (2) question IDs for new questions, (3) how timeline for their REST table changes aligns with our DB deployment schedule. | 1h |
| PEP-024 | Salesforce team internal work | Salesforce team. | AW: No action. | 0h |
| PEP-025 | End-to-end QA validation across all PEP items | AW + QA + all teams. | AW will validate: frontend rendering of all new/renamed tests, correct Salesforce field values (HighRisk, NotCoveredByMedicalPolicy, TestName), backend routing rules for all new test categories and rule changes. Requires stable test environment and all prior PEP items deployed. | 8h |
| PEP-026 | NIPT → NIPS terminology rename | AW (DB `test_categories` display name update + potentially `tests_controller.php` hardcoded "NIPT" string + `frontend_response.php` all rules checking `test_category == "NIPT"` + `TestList.jsx` conditional). | **Scope depends on one decision:** Does the internal category code also change from "NIPT" to "NIPS" or is this display text only? Display text only = DB update (~1h). Internal code change = 5 files to update simultaneously with risk of routing breakage (~5h). **Needs decision before scoping.** | 1h (display only) / 5h (internal code change) |
| PEP-027 | Routing rules for Prenatal Diagnostics: no $300 threshold, no Salesforce case, no "Will you move forward?" prompt | AW (`frontend_response.php` — new rule block for the Prenatal Diagnostics category). | Blocked on PEP-016 internal category string being confirmed. What message number does Prenatal Diagnostics always show? Is it always message 1 (show estimate, no follow-up action)? | 2.5h |
| PEP-028 | Don't show "I don't know" option for Prenatal Diagnostics section | AW (`InsuranceInfo.jsx` or question rendering logic — conditionally suppress the IDK option for this category). | Which question ID is this — the HighRisk question (ID 3) or a new question? How is suppression triggered — by test category or test ID? | 0.5h |
| PEP-029 | PEP team internal item | PEP team only. | AW: No action. | 0h |
| PEP-030 | Add new Message 10 to `frontend_response.php` responses array | AW (`frontend_response.php` — add new entry to `$responses` array + add rule condition that triggers it). | AW blocked until message text is approved by business/legal. What rule conditions trigger Message 10? | 1h |
| PEP-031 | Add 4 Fetal RhD routing rules to `frontend_response.php` | AW (`frontend_response.php` — insert 4 new rule blocks at specific positions in the if/elseif chain). | **Must deploy with PEP-032 in the same release** — inserting rules shifts sequential rule numbers used in Salesforce logs. What are the 4 rule conditions and which message number does each map to? | 2h |
| PEP-032 | Add Prenatal Diagnostics routing rule to `frontend_response.php` | AW (`frontend_response.php` — new rule block between current Rule 17 and Rule 18). | Combined PEP-031 deployment. Blocked on PEP-016 category code being confirmed. | 1h |
| PEP-033 | Confirm primary LCA codes for all new tests from Phoenix/Genetic Estimator team | Phoenix/Genetic Estimator team (primary). AW (update DB `cpt_bundle` column once codes confirmed). | Confirmed codes from this story unblock PEP-001, PEP-014, PEP-017, PEP-018, PEP-019, PEP-020. AW: no code action until codes are received; then DB update per test record. | 0.5h |

---

## AW Hour Summary

| Category | Items | Hours |
|----------|-------|-------|
| New test additions (DB inserts) | PEP-001, 014, 017, 018, 019, 020 | 21h |
| Test renames (DB updates) | PEP-004 through 012 | 8.5h |
| Logic / routing rule changes | PEP-003, 009, 015, 027, 028, 030, 031, 032 | 17h |
| Terminology / category changes | PEP-002, 013, 016, 026 | 7.5–11.5h |
| Validation / coordination | PEP-021, 023, 025, 033 | 10.5h |
| No AW action | PEP-022, 024, 029 | 0h |
| **Total** | | **~57–61h** |

> Range depends on PEP-026 scope decision (display text only vs. internal code change) and PEP-018 question complexity confirmation.

---

## Key Deployment Dependencies

1. **PEP-002 + PEP-003** must go live in the same release — logic change must coincide with Phoenix question update
2. **PEP-031 + PEP-032** must go live in the same release — both insert rules into the `frontend_response.php` chain and shift rule numbers
3. **PEP-016** (category string decision) must be resolved before PEP-027, PEP-028, and PEP-032 can be implemented
4. **PEP-033** (primary LCA codes) must be resolved before PEP-001, 014, 017, 018, 019, 020 can be completed
5. **PEP-011** (CF/SMA rename) — DB change and `frontend_response.php` string change must deploy simultaneously

---

## Open Questions Requiring External Input

| # | Question | Blocks |
|---|----------|--------|
| Q1 | Primary LCA CPT codes for all new tests | PEP-001, 014, 017, 018, 019, 020, 033 |
| Q2 | Does Phoenix question ID 9 change with AJP question update? | PEP-002, 003, 009 |
| Q3 | New NCBMP rule for AJP — is NCBMP=true only for Yes answer, or always? | PEP-003, 009 |
| Q4 | `index_in_phoenix` values for all new tests | PEP-001, 014, 018, 019, 020 |
| Q5 | Internal category code string for Prenatal Diagnostics section | PEP-016, 027, 028, 032 |
| Q6 | PEP-026: display text rename only, or internal "NIPT" code also changes to "NIPS"? | PEP-026 |
| Q7 | Exact new display name and description strings for all renamed tests (PEP-004 through 012) | PEP-004 through 012 |
| Q8 | Message 10 final approved text | PEP-030 |
| Q9 | Fetal RhD question ID and answer option IDs from Genetic Estimator team | PEP-015, 031 |
| Q10 | 4 Fetal RhD routing rule conditions and target message numbers | PEP-031 |
