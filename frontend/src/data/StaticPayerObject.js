const payerInfo = {
  insuranceStates: ["Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut", "Delaware", "District of Columbia", "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas", "Kentucky", "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota", "Mississippi", "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey", "New Mexico", "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon", "Pennsylvania", "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah", "Vermont", "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"],
  payersCompiled: [
    // {
    //   "id": "1",
    //   "phxPayerId": "R2MSC",
    //   "payerName": "Other",
    //   "displayName": "I don't have insurance",
    //   "tradingParterId": ""
    // },
    // {
    //   "id": "2",
    //   "phxPayerId": "R2MSC",
    //   "payerName": "Other",
    //   "displayName": "I don't see my insurance",
    //   "tradingParterId": ""
    // },
    {
      "id": "3",
      "phxPayerId": "1199N",
      "payerName": "Other",
      "displayName": "1199 National Benefit Fund",
      "tradingParterId": ""
    },
    {
      "id": "4",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "AARP Medicare Supplement Plan (Fixed or Hospital Indemnity)",
      "tradingParterId": "aarp_medicare_supplement"
    },
    {
      "id": "5",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Access Health Solutions",
      "tradingParterId": "access_health_solutions"
    },
    {
      "id": "6",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna",
      "tradingParterId": "aetna"
    },
    {
      "id": "7",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health",
      "tradingParterId": "aetna_better_health"
    },
    {
      "id": "8",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Illinois",
      "tradingParterId": "aetna_better_health_il"
    },
    {
      "id": "9",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Kentucky",
      "tradingParterId": "aetna_better_health_ky"
    },
    {
      "id": "10",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Louisiana",
      "tradingParterId": "aetna_better_health_la"
    },
    {
      "id": "11",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Michigan",
      "tradingParterId": "aetna_better_health_mi"
    },
    {
      "id": "12",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Nebraska",
      "tradingParterId": "aetna_better_health_ne"
    },
    {
      "id": "13",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of New Jersey",
      "tradingParterId": "aetna_better_health_nj"
    },
    {
      "id": "14",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of New York",
      "tradingParterId": "aetna_better_health_ny"
    },
    {
      "id": "15",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Ohio",
      "tradingParterId": "aetna_better_health_oh"
    },
    {
      "id": "16",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Pennsylvania",
      "tradingParterId": "aetna_better_health_pa"
    },
    {
      "id": "17",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Texas",
      "tradingParterId": "aetna_better_health_tx"
    },
    {
      "id": "18",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of Virginia",
      "tradingParterId": "aetna_better_health_va"
    },
    {
      "id": "19",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Better Health of West Virginia",
      "tradingParterId": "aetna_better_health_wv"
    },
    {
      "id": "20",
      "phxPayerId": "R2MSC",
      "payerName": "Aetna",
      "displayName": "Aetna Senior Supplemental (SSI)",
      "tradingParterId": "aetna_senior_supplemental"
    },
    {
      "id": "21",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Affinity Health Plan",
      "tradingParterId": "affinity_health_plan"
    },
    {
      "id": "22",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Affinity Medicare Advantage",
      "tradingParterId": "affinity_medicare_advantage"
    },
    {
      "id": "23",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Aflac",
      "tradingParterId": "aflac"
    },
    {
      "id": "24",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Aflac Medicare Supplement Insurance",
      "tradingParterId": "aflac_medicare"
    },
    {
      "id": "25",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Alabama Medicaid",
      "tradingParterId": "medicaid_al"
    },
    {
      "id": "26",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Alameda Alliance For Health",
      "tradingParterId": "alameda_alliance_for_health"
    },
    {
      "id": "27",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Alaska Medicaid",
      "tradingParterId": "medicaid_ak"
    },
    {
      "id": "28",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "All Savers Insurance",
      "tradingParterId": "all_savers_insurance"
    },
    {
      "id": "29",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Allegiance",
      "tradingParterId": "allegiance"
    },
    {
      "id": "30",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Allegiance Benefit Plan Management Incorporated",
      "tradingParterId": "allegiance_benefit _plan_management_inc"
    },
    {
      "id": "31",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Allied Benefit Systems Inc",
      "tradingParterId": "allied_benefit_systems"
    },
    {
      "id": "32",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "AllWays Health Partners (replaces Neighborhood)",
      "tradingParterId": "neighborhood_health_plan"
    },
    {
      "id": "33",
      "phxPayerId": "FHPUT",
      "payerName": "Other",
      "displayName": "Altius Health Plans",
      "tradingParterId": ""
    },
    {
      "id": "34",
      "phxPayerId": "AMBET",
      "payerName": "Ambetter",
      "displayName": "Ambetter (All States)",
      "tradingParterId": ""
    },
    {
      "id": "35",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Ameriben",
      "tradingParterId": "ameriben"
    },
    {
      "id": "36",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "American Medical Security (AMS - UnitedHealthcare Life)",
      "tradingParterId": "american_medical_security"
    },
    {
      "id": "37",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "American National Insurance",
      "tradingParterId": "american_national_insurance"
    },
    {
      "id": "38",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "American Postal Workers Union (APWU) Health Plan",
      "tradingParterId": "american_postal_union_health"
    },
    {
      "id": "39",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "American Republic Insurance Company (ARIC)",
      "tradingParterId": "american_republic"
    },
    {
      "id": "40",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Amerigroup",
      "tradingParterId": "amerigroup"
    },
    {
      "id": "41",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Amerigroup Community Care",
      "tradingParterId": "amerigroup_community_care"
    },
    {
      "id": "42",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "AmeriHealth Administrators",
      "tradingParterId": "amerihealth_administrators"
    },
    {
      "id": "43",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "AmeriHealth Caritas Iowa",
      "tradingParterId": "amerihealth_caritas_ia"
    },
    {
      "id": "44",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "AmeriHealth Caritas Louisiana",
      "tradingParterId": "amerihealth_caritas_la"
    },
    {
      "id": "45",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "AmeriHealth Caritas Pennsylvania",
      "tradingParterId": "amerihealth_caritas_pa"
    },
    {
      "id": "46",
      "phxPayerId": "AHDEF",
      "payerName": "Other",
      "displayName": "AmeriHealth HMO Delaware",
      "tradingParterId": ""
    },
    {
      "id": "47",
      "phxPayerId": "AHNJF",
      "payerName": "Other",
      "displayName": "AmeriHealth HMO New Jersey",
      "tradingParterId": ""
    },
    {
      "id": "48",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Arizona Medicaid (AHCCCS)",
      "tradingParterId": "medicaid_az"
    },
    {
      "id": "49",
      "phxPayerId": "UNITE",
      "payerName": "United Healthcare",
      "displayName": "Arizona Physicians IPA (United Healthcare Community Plan AZ)",
      "tradingParterId": ""
    },
    {
      "id": "50",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Arkansas Medicaid",
      "tradingParterId": "medicaid_ar"
    },
    {
      "id": "51",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Arnett Health Plan",
      "tradingParterId": "arnett_health_plan"
    },
    {
      "id": "52",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "ASR Health Benefits",
      "tradingParterId": "asr_health_benefits"
    },
    {
      "id": "53",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Asuris Northwest Health",
      "tradingParterId": "asuris_northwest_health"
    },
    {
      "id": "54",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Aultcare",
      "tradingParterId": "aultcare"
    },
    {
      "id": "55",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Automated Benefit Services",
      "tradingParterId": "automated_benefit_services"
    },
    {
      "id": "56",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Avmed Health Plan",
      "tradingParterId": "avmed_health_plan"
    },
    {
      "id": "57",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Banker's Life and Casualty",
      "tradingParterId": "bankers_life_and_casualty"
    },
    {
      "id": "58",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Banner Health Plan",
      "tradingParterId": "banner_health_plans"
    },
    {
      "id": "59",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Best Choice Health Plan",
      "tradingParterId": "best_choice_health_plan"
    },
    {
      "id": "60",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Better Health Plan (Unison)",
      "tradingParterId": "better_health_plan"
    },
    {
      "id": "61",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Bind Benefits",
      "tradingParterId": "bind_benefits"
    },
    {
      "id": "62",
      "phxPayerId": "FEPVA",
      "payerName": "Blue Cross and Blue Shield Federal Employee Program",
      "displayName": "Blue Cross and Blue Shield Federal Employee Program",
      "tradingParterId": ""
    },
    {
      "id": "63",
      "phxPayerId": "BSPMD",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Alabama",
      "tradingParterId": ""
    },
    {
      "id": "64",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Alaska (Premera)",
      "tradingParterId": "premera_blue_cross_blue_shield_ak"
    },
    {
      "id": "65",
      "phxPayerId": "BCAZA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Arizona",
      "tradingParterId": ""
    },
    {
      "id": "66",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Arkansas",
      "tradingParterId": "blue_cross_blue_shield_ar"
    },
    {
      "id": "67",
      "phxPayerId": "BCPRM",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Colorado (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "68",
      "phxPayerId": "BSCTA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Connecticut (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "69",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of DC (CareFirst)",
      "tradingParterId": "blue_cross_blue_shield_dc"
    },
    {
      "id": "70",
      "phxPayerId": "BSDEA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Delaware",
      "tradingParterId": ""
    },
    {
      "id": "71",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Florida",
      "tradingParterId": "blue_cross_blue_shield_fl"
    },
    {
      "id": "72",
      "phxPayerId": "BSGAA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Georgia",
      "tradingParterId": ""
    },
    {
      "id": "73",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Hawaii (Hawaii Medical Service Association)",
      "tradingParterId": "blue_cross_blue_shield_hi"
    },
    {
      "id": "74",
      "phxPayerId": "BSILA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Illinois",
      "tradingParterId": ""
    },
    {
      "id": "75",
      "phxPayerId": "BSINA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Indiana (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "76",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Iowa (Wellmark)",
      "tradingParterId": "wellmark_blue_cross_blue_shield_ia"
    },
    {
      "id": "77",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Kansas",
      "tradingParterId": "blue_cross_blue_shield_ks"
    },
    {
      "id": "78",
      "phxPayerId": "BSMOK",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Kansas City",
      "tradingParterId": ""
    },
    {
      "id": "79",
      "phxPayerId": "BSKYI",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Kentucky (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "80",
      "phxPayerId": "BSLAA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Louisiana",
      "tradingParterId": ""
    },
    {
      "id": "81",
      "phxPayerId": "BSMEE",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Maine (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "82",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Maryland (CareFirst)",
      "tradingParterId": "blue_cross_blue_shield_md"
    },
    {
      "id": "83",
      "phxPayerId": "BSMAA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Massachusetts",
      "tradingParterId": ""
    },
    {
      "id": "84",
      "phxPayerId": "BSMIA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Michigan",
      "tradingParterId": ""
    },
    {
      "id": "85",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Minnesota",
      "tradingParterId": "blue_cross_blue_shield_mn"
    },
    {
      "id": "86",
      "phxPayerId": "BSMSA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Mississippi",
      "tradingParterId": ""
    },
    {
      "id": "87",
      "phxPayerId": "ABCBS",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Missouri (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "88",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Montana",
      "tradingParterId": "blue_cross_blue_shield_mt"
    },
    {
      "id": "89",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Nebraska",
      "tradingParterId": "blue_cross_blue_shield_ne"
    },
    {
      "id": "90",
      "phxPayerId": "NVANT",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Nevada (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "91",
      "phxPayerId": "BSNHA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of New Hampshire (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "92",
      "phxPayerId": "BSNJA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of New Jersey (Horizon)",
      "tradingParterId": ""
    },
    {
      "id": "93",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of New Mexico",
      "tradingParterId": "blue_cross_blue_shield_nm"
    },
    {
      "id": "94",
      "phxPayerId": "EMPBS",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of New York (Empire)",
      "tradingParterId": ""
    },
    {
      "id": "95",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of New York (Excellus)",
      "tradingParterId": "excellus_blue_cross_blue_shield"
    },
    {
      "id": "96",
      "phxPayerId": "BSNCA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of North Carolina",
      "tradingParterId": ""
    },
    {
      "id": "97",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of North Dakota",
      "tradingParterId": "blue_cross_blue_shield_nd"
    },
    {
      "id": "98",
      "phxPayerId": "CMICA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Ohio (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "99",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Oklahoma",
      "tradingParterId": "blue_cross_blue_shield_ok"
    },
    {
      "id": "100",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Oregon (Regence)",
      "tradingParterId": "regence_bluecross_blueshield_or"
    },
    {
      "id": "101",
      "phxPayerId": "BSPAA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Pennsylvania (Highmark)",
      "tradingParterId": ""
    },
    {
      "id": "102",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Rhode Island",
      "tradingParterId": "blue_cross_blue_shield_ri"
    },
    {
      "id": "103",
      "phxPayerId": "BSSCA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of South Carolina",
      "tradingParterId": ""
    },
    {
      "id": "104",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of South Dakota (Wellmark)",
      "tradingParterId": "wellmark_blue_cross_blue_shield_sd"
    },
    {
      "id": "105",
      "phxPayerId": "BSTNC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Tennessee",
      "tradingParterId": ""
    },
    {
      "id": "106",
      "phxPayerId": "BSTXA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Texas",
      "tradingParterId": ""
    },
    {
      "id": "107",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Utah (Regence)",
      "tradingParterId": "bluecross_blueshield_utah"
    },
    {
      "id": "108",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Vermont",
      "tradingParterId": "blue_cross_blue_shield_vt"
    },
    {
      "id": "109",
      "phxPayerId": "BSVAA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Virginia (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "110",
      "phxPayerId": "MTBSW",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of West Virginia (Highmark)",
      "tradingParterId": ""
    },
    {
      "id": "111",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Western New York",
      "tradingParterId": "blue_cross_blue_shield_wny"
    },
    {
      "id": "112",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Western New York Federal Employee Plan",
      "tradingParterId": "blue_cross_blue_shield_wny_fep"
    },
    {
      "id": "113",
      "phxPayerId": "BSWIA",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Wisconsin (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "114",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross and Blue Shield of Wyoming",
      "tradingParterId": "blue_cross_blue_shield_wy"
    },
    {
      "id": "115",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross Blue Shield Medicare Advantage",
      "tradingParterId": "bcbs_medicare_advantage"
    },
    {
      "id": "116",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross Blue Shield Minnesota Medicaid",
      "tradingParterId": "bcbs_mn_medicaid"
    },
    {
      "id": "117",
      "phxPayerId": "GPPPN",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross Independence (IBC)",
      "tradingParterId": ""
    },
    {
      "id": "118",
      "phxPayerId": "BCOOS",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross of California (Anthem)",
      "tradingParterId": ""
    },
    {
      "id": "119",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross of Idaho",
      "tradingParterId": "blue_cross_idaho"
    },
    {
      "id": "120",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross of Pennsylvania (Capital)",
      "tradingParterId": "capital_blue_cross"
    },
    {
      "id": "121",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Cross of Washington (Premera)",
      "tradingParterId": "premera_blue_cross"
    },
    {
      "id": "122",
      "phxPayerId": "BBOOS",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Shield of California",
      "tradingParterId": ""
    },
    {
      "id": "123",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Shield of Idaho (Regence)",
      "tradingParterId": "regence_blueshield_id"
    },
    {
      "id": "124",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Shield of Northeastern New York",
      "tradingParterId": "blueshield_of_northeastern_new_york"
    },
    {
      "id": "125",
      "phxPayerId": "REGEN",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Blue Shield of Washington (Regence)",
      "tradingParterId": ""
    },
    {
      "id": "126",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "BlueShield of Northeastern New York Federal Employee Plan",
      "tradingParterId": "blueshield_of_northeastern_new_york_fep"
    },
    {
      "id": "127",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Boon Chapman",
      "tradingParterId": "boon_chapman"
    },
    {
      "id": "128",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Boon Group",
      "tradingParterId": "boon_group"
    },
    {
      "id": "129",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Boston Medical Center Health Plan",
      "tradingParterId": "boston_medical_center"
    },
    {
      "id": "130",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Bravo Health",
      "tradingParterId": "bravo_health"
    },
    {
      "id": "131",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "BridgeSpan Health",
      "tradingParterId": "bridgespan"
    },
    {
      "id": "132",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Bridgeway Health Solutions",
      "tradingParterId": "bridgeway_health_solutions"
    },
    {
      "id": "133",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Bright Health",
      "tradingParterId": "bright_health"
    },
    {
      "id": "134",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Buckeye Community Health Plan",
      "tradingParterId": "buckeye_community_health_plan"
    },
    {
      "id": "135",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "California Health and Wellness",
      "tradingParterId": "california_health_wellness"
    },
    {
      "id": "136",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "California Medicaid (Medi-Cal)",
      "tradingParterId": "medicaid_ca"
    },
    {
      "id": "137",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Capital District Physicians Health Plan (CDPHP)",
      "tradingParterId": "cdphp"
    },
    {
      "id": "138",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Capital Health Plan",
      "tradingParterId": "capital_health_plan"
    },
    {
      "id": "139",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Care Improvement Plus",
      "tradingParterId": "care_improvement_plus"
    },
    {
      "id": "140",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Care1st Health Plan Arizona",
      "tradingParterId": "care1st_health_az"
    },
    {
      "id": "141",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Care1st Health Plan of California",
      "tradingParterId": "care1st"
    },
    {
      "id": "142",
      "phxPayerId": "COVEX",
      "payerName": "Other",
      "displayName": "Carelink (Coventry)",
      "tradingParterId": ""
    },
    {
      "id": "143",
      "phxPayerId": "COVEX",
      "payerName": "Other",
      "displayName": "Carelink Medicaid (Coventry)",
      "tradingParterId": ""
    },
    {
      "id": "144",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CarePlus Health Plan",
      "tradingParterId": "careplus_health_plan"
    },
    {
      "id": "145",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CareSource of Indiana",
      "tradingParterId": "caresource_in"
    },
    {
      "id": "146",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CareSource of Ohio",
      "tradingParterId": "caresource_oh"
    },
    {
      "id": "147",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Celtic Insurance Company",
      "tradingParterId": "celtic_insurance_co"
    },
    {
      "id": "148",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CeltiCare Health Plan",
      "tradingParterId": "celticare_health_plan"
    },
    {
      "id": "149",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Arizona",
      "tradingParterId": "cenpatico_az"
    },
    {
      "id": "150",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Florida",
      "tradingParterId": "cenpatico_fl"
    },
    {
      "id": "151",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Georgia",
      "tradingParterId": "cenpatico_ga"
    },
    {
      "id": "152",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Illinois",
      "tradingParterId": "cenpatico_il"
    },
    {
      "id": "153",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Indiana",
      "tradingParterId": "cenpatico_in"
    },
    {
      "id": "154",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Kansas",
      "tradingParterId": "cenpatico_ks"
    },
    {
      "id": "155",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Kentucky",
      "tradingParterId": "cenpatico_ky"
    },
    {
      "id": "156",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Massachusetts",
      "tradingParterId": "cenpatico_ma"
    },
    {
      "id": "157",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Montana",
      "tradingParterId": "cenpatico_mo"
    },
    {
      "id": "158",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Ohio",
      "tradingParterId": "cenpatico_oh"
    },
    {
      "id": "159",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health South Carolina",
      "tradingParterId": "cenpatico_sc"
    },
    {
      "id": "160",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Texas",
      "tradingParterId": "cenpatico_tx"
    },
    {
      "id": "161",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cenpatico Behavioral Health Wisconsin",
      "tradingParterId": "cenpatico_wi"
    },
    {
      "id": "162",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Center for Medicare and Medicaid (CMS)",
      "tradingParterId": "medicare_national"
    },
    {
      "id": "163",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Central California Alliance for Health",
      "tradingParterId": "central_california_health_alliance"
    },
    {
      "id": "164",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "ChampVA - Health Administration Center (HAC)",
      "tradingParterId": "champva"
    },
    {
      "id": "165",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "CHC - Coventry Health and Life Tennessee",
      "tradingParterId": "coventry_health_and_life_tennessee"
    },
    {
      "id": "166",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Children's Medical Center Health Plan",
      "tradingParterId": "childrens_medical_center_health_plan"
    },
    {
      "id": "167",
      "phxPayerId": "CIGNH",
      "payerName": "Cigna",
      "displayName": "Cigna",
      "tradingParterId": ""
    },
    {
      "id": "168",
      "phxPayerId": "R2MSC",
      "payerName": "Cigna",
      "displayName": "Cigna-HealthSpring",
      "tradingParterId": "cigna_health_spring"
    },
    {
      "id": "169",
      "phxPayerId": "CGCPF",
      "payerName": "Cigna",
      "displayName": "Cigna-HMO",
      "tradingParterId": ""
    },
    {
      "id": "170",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Colorado Medicaid",
      "tradingParterId": "medicaid_co"
    },
    {
      "id": "171",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Community First Health Plan",
      "tradingParterId": "community_first"
    },
    {
      "id": "172",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Community Health Choice",
      "tradingParterId": "community_health_choice"
    },
    {
      "id": "173",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Community Health Plan of Washington",
      "tradingParterId": "community_health_plan_wa"
    },
    {
      "id": "174",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CommunityCare of Oklahoma",
      "tradingParterId": "community_care_ok"
    },
    {
      "id": "175",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "ConnectiCare",
      "tradingParterId": "connecticare"
    },
    {
      "id": "176",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "ConnectiCare Medicare Advantage",
      "tradingParterId": "connecticare_medicare"
    },
    {
      "id": "177",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Connecticut Medicaid",
      "tradingParterId": "medicaid_ct"
    },
    {
      "id": "178",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Continental General Insurance Company",
      "tradingParterId": "continental_general_insurance"
    },
    {
      "id": "179",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cook Children's Health Plan",
      "tradingParterId": "cook_childrens_health"
    },
    {
      "id": "180",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cooperative Benefit Administrators",
      "tradingParterId": "cooperative_benefit"
    },
    {
      "id": "181",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Coordinated Care",
      "tradingParterId": "coordinated_care"
    },
    {
      "id": "182",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CoreSource ('IL','IN','MD','NC','PA')",
      "tradingParterId": "coresource_il_in_md_nc_pa"
    },
    {
      "id": "183",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CoreSource Little Rock",
      "tradingParterId": "coresource_little_rock"
    },
    {
      "id": "184",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "CoreSource Ohio",
      "tradingParterId": "coresource_oh"
    },
    {
      "id": "185",
      "phxPayerId": "COVEX",
      "payerName": "Coventry",
      "displayName": "Coventry",
      "tradingParterId": ""
    },
    {
      "id": "186",
      "phxPayerId": "COVEX",
      "payerName": "Coventry",
      "displayName": "Coventry Advantra",
      "tradingParterId": ""
    },
    {
      "id": "187",
      "phxPayerId": "COVEX",
      "payerName": "Coventry",
      "displayName": "Coventry Cares",
      "tradingParterId": ""
    },
    {
      "id": "188",
      "phxPayerId": "COVEX",
      "payerName": "Coventry",
      "displayName": "Coventry Cares of Michigan (OmniCare)",
      "tradingParterId": ""
    },
    {
      "id": "189",
      "phxPayerId": "SHPPO",
      "payerName": "Coventry",
      "displayName": "Coventry Cares of Virginia",
      "tradingParterId": ""
    },
    {
      "id": "190",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care Group Health Plan",
      "tradingParterId": "chc_group_health_plan"
    },
    {
      "id": "191",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Delaware",
      "tradingParterId": "coventry_health_care_de"
    },
    {
      "id": "192",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Florida",
      "tradingParterId": "coventry_health_care_fl"
    },
    {
      "id": "193",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Georgia",
      "tradingParterId": "coventry_health_care_ga"
    },
    {
      "id": "194",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Illinois",
      "tradingParterId": "coventry_health_care_il"
    },
    {
      "id": "195",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Iowa",
      "tradingParterId": "coventry_health_care_ia"
    },
    {
      "id": "196",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Kansas",
      "tradingParterId": "coventry_health_care_ks"
    },
    {
      "id": "197",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Louisiana",
      "tradingParterId": "coventry_health_care_la"
    },
    {
      "id": "198",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Missouri",
      "tradingParterId": "coventry_health_care_mo"
    },
    {
      "id": "199",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Nebraska",
      "tradingParterId": "coventry_health_care_ne"
    },
    {
      "id": "200",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Oklahoma",
      "tradingParterId": "coventry_health_care_ok"
    },
    {
      "id": "201",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of Texas",
      "tradingParterId": "coventry_health_care_tx"
    },
    {
      "id": "202",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Health Care of the Carolinas (Wellpath)",
      "tradingParterId": "coventry_health_care_carolinas"
    },
    {
      "id": "203",
      "phxPayerId": "R2MSC",
      "payerName": "Coventry",
      "displayName": "Coventry Healthcare National Network",
      "tradingParterId": "coventry_healthcare_national_network"
    },
    {
      "id": "204",
      "phxPayerId": "COVEX",
      "payerName": "Coventry",
      "displayName": "Coventry One",
      "tradingParterId": ""
    },
    {
      "id": "205",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Cypress Benefit Administrators",
      "tradingParterId": "cypress_benefits"
    },
    {
      "id": "206",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "DC Medicaid",
      "tradingParterId": "medicaid_dc"
    },
    {
      "id": "207",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Dean Health Plan",
      "tradingParterId": "dean_health_plan"
    },
    {
      "id": "208",
      "phxPayerId": "UNITE",
      "payerName": "Other",
      "displayName": "Definity Health (United)",
      "tradingParterId": ""
    },
    {
      "id": "209",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Delaware Medicaid",
      "tradingParterId": "medicaid_de"
    },
    {
      "id": "210",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Deseret Mutual",
      "tradingParterId": "deseret_mutual"
    },
    {
      "id": "211",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Driscoll Children's Health Plan",
      "tradingParterId": "driscoll_childrens_health"
    },
    {
      "id": "212",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "EBSO Benefits",
      "tradingParterId": "ebso_benefits"
    },
    {
      "id": "213",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Elderplan",
      "tradingParterId": "elderplan"
    },
    {
      "id": "214",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Emblem Health",
      "tradingParterId": "emblem_health"
    },
    {
      "id": "215",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Employee Benefit Management Services",
      "tradingParterId": "ebms"
    },
    {
      "id": "216",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Essence Healthcare",
      "tradingParterId": "essence_healthcare"
    },
    {
      "id": "217",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Fallon Community Health Plan",
      "tradingParterId": "fallon_community_health"
    },
    {
      "id": "218",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Farm Bureau Health Plans",
      "tradingParterId": "farm_bureau_health_plans"
    },
    {
      "id": "219",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Federated Mutual Insurance Company",
      "tradingParterId": "federated_mutual"
    },
    {
      "id": "220",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Fidelis Care of New York",
      "tradingParterId": "fidelis_care_ny"
    },
    {
      "id": "221",
      "phxPayerId": "FHNTL",
      "payerName": "Coventry",
      "displayName": "First Health (Coventry)",
      "tradingParterId": ""
    },
    {
      "id": "222",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Florida Health Care Plan",
      "tradingParterId": "florida_health_care_plan"
    },
    {
      "id": "223",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Florida Medicaid",
      "tradingParterId": "medicaid_fl"
    },
    {
      "id": "224",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Gateway Health Medicare Assured",
      "tradingParterId": "gateway_health_medicare_assured"
    },
    {
      "id": "225",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Gateway Health Pennsylvania",
      "tradingParterId": "gateway_health_pa"
    },
    {
      "id": "226",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Geisinger Health Plan",
      "tradingParterId": "geisinger_health"
    },
    {
      "id": "227",
      "phxPayerId": "GANTL",
      "payerName": "Other",
      "displayName": "General American Life Insurance",
      "tradingParterId": ""
    },
    {
      "id": "228",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Georgia Medicaid",
      "tradingParterId": "medicaid_ga"
    },
    {
      "id": "229",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Globe Life and Accident Insurance Company",
      "tradingParterId": "globe_life"
    },
    {
      "id": "230",
      "phxPayerId": "GOLDR",
      "payerName": "United Healthcare",
      "displayName": "Golden Rule",
      "tradingParterId": ""
    },
    {
      "id": "231",
      "phxPayerId": "GEUHT",
      "payerName": "Other",
      "displayName": "Government Employees Health Association (GEHA)",
      "tradingParterId": ""
    },
    {
      "id": "232",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Granite State Health Plan",
      "tradingParterId": "granite_state_health_plan"
    },
    {
      "id": "233",
      "phxPayerId": "GRWNT",
      "payerName": "Great West Healthcare",
      "displayName": "Great West Healthcare",
      "tradingParterId": ""
    },
    {
      "id": "234",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Group and Pension Administrators",
      "tradingParterId": "group_pension_administrators"
    },
    {
      "id": "235",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Group Health Cooperative",
      "tradingParterId": "group_health_cooperative"
    },
    {
      "id": "236",
      "phxPayerId": "GHPMO",
      "payerName": "Other",
      "displayName": "Group Health Plan",
      "tradingParterId": ""
    },
    {
      "id": "237",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Harken Health",
      "tradingParterId": "harken_health"
    },
    {
      "id": "238",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Harmony Health Plan of Illinois",
      "tradingParterId": "harmony_health_plan_illinois"
    },
    {
      "id": "239",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Harmony Health Plan of Indiana",
      "tradingParterId": "harmony_health_plan_indiana"
    },
    {
      "id": "240",
      "phxPayerId": "HRVCH",
      "payerName": "Other",
      "displayName": "Harvard Pilgrim Health Care",
      "tradingParterId": ""
    },
    {
      "id": "241",
      "phxPayerId": "HPLMA",
      "payerName": "Other",
      "displayName": "Harvard Pilgrim Passport Connect",
      "tradingParterId": ""
    },
    {
      "id": "242",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Hawaii Mainland Administrators",
      "tradingParterId": "hawaii_mainland_administrators"
    },
    {
      "id": "243",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Hawaii Medicaid",
      "tradingParterId": "medicaid_hi"
    },
    {
      "id": "244",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Alliance Medical Plan of Illinois",
      "tradingParterId": "health_alliance_il"
    },
    {
      "id": "245",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Alliance Plan of Michigan",
      "tradingParterId": "health_alliance_mi"
    },
    {
      "id": "246",
      "phxPayerId": "HLTPA",
      "payerName": "Other",
      "displayName": "Health Assurance (Western Pennsylvania Only)",
      "tradingParterId": ""
    },
    {
      "id": "247",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Choice of Arizona",
      "tradingParterId": "health_choice_az"
    },
    {
      "id": "248",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health First Health Plans",
      "tradingParterId": "health_first_health_plans"
    },
    {
      "id": "249",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Net ('AZ','CT','NJ','NY','PA')",
      "tradingParterId": "health_net_az_ct_nj_ny_pa"
    },
    {
      "id": "250",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Net National",
      "tradingParterId": "health_net_national"
    },
    {
      "id": "251",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Net of California",
      "tradingParterId": "health_net_ca"
    },
    {
      "id": "252",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health New England",
      "tradingParterId": "health_new_england"
    },
    {
      "id": "253",
      "phxPayerId": "HPCIG",
      "payerName": "Other",
      "displayName": "Health Partners (Cigna)",
      "tradingParterId": ""
    },
    {
      "id": "254",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Partners Plans",
      "tradingParterId": "Inc"
    },
    {
      "id": "255",
      "phxPayerId": "UNITE",
      "payerName": "United Healthcare",
      "displayName": "Health Plan of Nevada (United Healthcare Nevada)",
      "tradingParterId": ""
    },
    {
      "id": "256",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Plan of San Joaquin",
      "tradingParterId": "health_plan_san_joaquin"
    },
    {
      "id": "257",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Plan of San Mateo",
      "tradingParterId": "health_plan_san_mateo"
    },
    {
      "id": "258",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Health Tradition Health Plan",
      "tradingParterId": "health_tradition_health_plan"
    },
    {
      "id": "259",
      "phxPayerId": "HPASS",
      "payerName": "Other",
      "displayName": "HealthAmerica",
      "tradingParterId": ""
    },
    {
      "id": "260",
      "phxPayerId": "HLTAC",
      "payerName": "Other",
      "displayName": "Healthcare USA (Coventry)",
      "tradingParterId": ""
    },
    {
      "id": "261",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthChoice of Oklahoma",
      "tradingParterId": "health_choice_ok"
    },
    {
      "id": "262",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthComp",
      "tradingParterId": "healthcomp_administrators"
    },
    {
      "id": "263",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthEase of Florida (WellCare)",
      "tradingParterId": "healthease"
    },
    {
      "id": "264",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthEZ",
      "tradingParterId": "health_ez"
    },
    {
      "id": "265",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Healthfirst of New York",
      "tradingParterId": "healthfirst_ny"
    },
    {
      "id": "266",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Healthgram",
      "tradingParterId": "healthgram"
    },
    {
      "id": "267",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthNow New York",
      "tradingParterId": "healthnow"
    },
    {
      "id": "268",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthPartners Minnesota",
      "tradingParterId": "health_partners_mn"
    },
    {
      "id": "269",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthScope Benefits",
      "tradingParterId": "healthscope_benefits"
    },
    {
      "id": "270",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "HealthSmart Benefit Solutions",
      "tradingParterId": "healthsmart_benefit"
    },
    {
      "id": "271",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Home State Health Plan",
      "tradingParterId": "home_state_health_plan"
    },
    {
      "id": "272",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Hometown Health",
      "tradingParterId": "hometown_health"
    },
    {
      "id": "273",
      "phxPayerId": "TPABS",
      "payerName": "Other",
      "displayName": "Horizon Healthcare Administrators",
      "tradingParterId": ""
    },
    {
      "id": "274",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Horizon NJ Health",
      "tradingParterId": "horizon_nj_health"
    },
    {
      "id": "275",
      "phxPayerId": "HHPKY",
      "payerName": "Humana",
      "displayName": "Humana",
      "tradingParterId": ""
    },
    {
      "id": "276",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Idaho Medicaid",
      "tradingParterId": "medicaid_id"
    },
    {
      "id": "277",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Illinicare Health Plan",
      "tradingParterId": "illinicare_health_plan"
    },
    {
      "id": "278",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Illinois Medicaid",
      "tradingParterId": "medicaid_il"
    },
    {
      "id": "279",
      "phxPayerId": "INDEP",
      "payerName": "Other",
      "displayName": "Independence Administrators",
      "tradingParterId": ""
    },
    {
      "id": "280",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Independent Health",
      "tradingParterId": "independent_health"
    },
    {
      "id": "281",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Indiana Medicaid",
      "tradingParterId": "medicaid_in"
    },
    {
      "id": "282",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Inland Empire Health Plan",
      "tradingParterId": "inland_empire_health"
    },
    {
      "id": "283",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Iowa Medicaid",
      "tradingParterId": "medicaid_ia"
    },
    {
      "id": "284",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "John Hopkins Healthcare LLC",
      "tradingParterId": "john_hopkins_health_care"
    },
    {
      "id": "285",
      "phxPayerId": "R2MSC",
      "payerName": "Kaiser",
      "displayName": "Kaiser Foundation Health Plan of the Northwest",
      "tradingParterId": "kaiser_permanente_northwest"
    },
    {
      "id": "286",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Kaiser Permanente of Colorado",
      "tradingParterId": "kaiser_permanente_co"
    },
    {
      "id": "287",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Kaiser Permanente of Georgia",
      "tradingParterId": "kaiser_permanente_ga"
    },
    {
      "id": "288",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Kaiser Permanente of Northern CA",
      "tradingParterId": "kaiser_permanente_northern_ca"
    },
    {
      "id": "289",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Kaiser Permanente of Southern CA",
      "tradingParterId": "kaiser_permanente_southern_ca"
    },
    {
      "id": "290",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Kaiser Permanente of the Mid Atlantic",
      "tradingParterId": "kaiser_permanente_mid_atlantic"
    },
    {
      "id": "291",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Kaiser Permanente of Washington",
      "tradingParterId": "kaiser_permanente_wa"
    },
    {
      "id": "292",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Kansas Medicaid",
      "tradingParterId": "medicaid_ks"
    },
    {
      "id": "293",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Kentucky Medicaid",
      "tradingParterId": "medicaid_ky"
    },
    {
      "id": "294",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Key Benefit Administrators",
      "tradingParterId": "key_benefit"
    },
    {
      "id": "295",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Keystone First",
      "tradingParterId": "keystone_first"
    },
    {
      "id": "296",
      "phxPayerId": "KEYEX",
      "payerName": "Other",
      "displayName": "Keystone Health Plan East",
      "tradingParterId": ""
    },
    {
      "id": "297",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "LA Care Health Plan",
      "tradingParterId": "la_care_health_plan"
    },
    {
      "id": "298",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Liberty National Life Insurance Company",
      "tradingParterId": "liberty_national"
    },
    {
      "id": "299",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Lifetime Benefit Solutions",
      "tradingParterId": "lifetime_benefit_solutions"
    },
    {
      "id": "300",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "LifeWise Health Plan of Oregon",
      "tradingParterId": "lifewise_or"
    },
    {
      "id": "301",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "LifeWise Health Plan of Washington",
      "tradingParterId": "lifewise_wa"
    },
    {
      "id": "302",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Lincoln Financial Group",
      "tradingParterId": "lincoln_financial"
    },
    {
      "id": "303",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Loomis Company",
      "tradingParterId": "loomis_company"
    },
    {
      "id": "304",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Louisiana Healthcare Connections",
      "tradingParterId": "la_healthcare_connections"
    },
    {
      "id": "305",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Louisiana Medicaid",
      "tradingParterId": "medicaid_la"
    },
    {
      "id": "306",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Magellan Health",
      "tradingParterId": "magellan_health"
    },
    {
      "id": "307",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Magnacare",
      "tradingParterId": "magnacare"
    },
    {
      "id": "308",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Magnolia Health Plan",
      "tradingParterId": "magnolia_health_plan"
    },
    {
      "id": "309",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "MAHP MAMSI (United)",
      "tradingParterId": "mahp"
    },
    {
      "id": "310",
      "phxPayerId": "MLHND",
      "payerName": "Other",
      "displayName": "Mail Handlers Benefit Plan (MHBP)",
      "tradingParterId": ""
    },
    {
      "id": "311",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Maine Medicaid",
      "tradingParterId": "medicaid_me"
    },
    {
      "id": "312",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Managed Health Services Indiana",
      "tradingParterId": "managed_health_services_in"
    },
    {
      "id": "313",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Managed Health Services Insurance Corp",
      "tradingParterId": "managed_health_services_insurance_corp"
    },
    {
      "id": "314",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Managed Health Services Wisconsin",
      "tradingParterId": "managed_health_services_wi"
    },
    {
      "id": "315",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "MAPFRE Life Puerto Rico",
      "tradingParterId": "mapfre_life"
    },
    {
      "id": "316",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Maricopa Health Plan (Arizona)",
      "tradingParterId": "maricopa_health_plan_az"
    },
    {
      "id": "317",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Maryland Medicaid",
      "tradingParterId": "medicaid_md"
    },
    {
      "id": "318",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Massachusetts Medicaid (MassHealth)",
      "tradingParterId": "medicaid_ma"
    },
    {
      "id": "319",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Medcost Benefit Services",
      "tradingParterId": "medcost"
    },
    {
      "id": "320",
      "phxPayerId": "MEDCC",
      "payerName": "Other",
      "displayName": "Medica Health Plans",
      "tradingParterId": ""
    },
    {
      "id": "321",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Medica Health Plans",
      "tradingParterId": "medica_health_plans"
    },
    {
      "id": "322",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Medica Health Plans (IFB Groups)",
      "tradingParterId": "medica_health_plans_ifb_groups"
    },
    {
      "id": "323",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Medi-Cal",
      "tradingParterId": "cal_optima_medi_cal"
    },
    {
      "id": "324",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Medical Mutual of Ohio",
      "tradingParterId": "medical_mutual_oh"
    },
    {
      "id": "325",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Medico Insurance Company",
      "tradingParterId": "medico_insurance_company"
    },
    {
      "id": "326",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "MedStar Family Choice",
      "tradingParterId": "medstar_family_choice"
    },
    {
      "id": "327",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Mercy Care Plan",
      "tradingParterId": "mercy_care_plan"
    },
    {
      "id": "328",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Meritain Health",
      "tradingParterId": "meritain_health"
    },
    {
      "id": "329",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Metro Plus Health Plan",
      "tradingParterId": "metro_plus_health_plan"
    },
    {
      "id": "330",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Michigan Medicaid",
      "tradingParterId": "medicaid_mi"
    },
    {
      "id": "331",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Minnesota Medicaid",
      "tradingParterId": "medicaid_mn"
    },
    {
      "id": "332",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Mississippi Medicaid",
      "tradingParterId": "medicaid_ms"
    },
    {
      "id": "333",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Missouri Medicaid",
      "tradingParterId": "medicaid_mo"
    },
    {
      "id": "336",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "MODA Health Plan",
      "tradingParterId": "moda_health"
    },
    {
      "id": "337",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of California",
      "tradingParterId": "molina_health_care_ca"
    },
    {
      "id": "338",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Florida",
      "tradingParterId": "molina_health_care_fl"
    },
    {
      "id": "339",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Illinois",
      "tradingParterId": "molina_health_care_il"
    },
    {
      "id": "340",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Michigan",
      "tradingParterId": "molina_health_care_mi"
    },
    {
      "id": "341",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of New Mexico",
      "tradingParterId": "molina_health_care_nm"
    },
    {
      "id": "342",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Ohio",
      "tradingParterId": "molina_health_care_oh"
    },
    {
      "id": "343",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Puerto Rico",
      "tradingParterId": "molina_health_care_pr"
    },
    {
      "id": "344",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of South Carolina",
      "tradingParterId": "molina_health_care_sc"
    },
    {
      "id": "345",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Texas",
      "tradingParterId": "molina_health_care_tx"
    },
    {
      "id": "346",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Utah",
      "tradingParterId": "molina_health_care_ut"
    },
    {
      "id": "347",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Washington",
      "tradingParterId": "molina_health_care_wa"
    },
    {
      "id": "348",
      "phxPayerId": "R2MSC",
      "payerName": "Molina Healthcare",
      "displayName": "Molina Healthcare of Wisconsin",
      "tradingParterId": "molina_health_care_wi"
    },
    {
      "id": "349",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Montana Medicaid",
      "tradingParterId": "medicaid_mt"
    },
    {
      "id": "350",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Montefiore Contract Management Organization",
      "tradingParterId": "montefiore_contract_management_organization"
    },
    {
      "id": "351",
      "phxPayerId": "FFMPH",
      "payerName": "Other",
      "displayName": "Motion Picture Health and Welfare",
      "tradingParterId": ""
    },
    {
      "id": "352",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Mutual Health Services",
      "tradingParterId": "mutual_health_services"
    },
    {
      "id": "353",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Mutual of Omaha",
      "tradingParterId": "mutual_omaha"
    },
    {
      "id": "354",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "MVP Health Care",
      "tradingParterId": "mvp_health_care"
    },
    {
      "id": "355",
      "phxPayerId": "NALCI",
      "payerName": "Other",
      "displayName": "National Association of Letter Carriers (NALC)",
      "tradingParterId": ""
    },
    {
      "id": "356",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Nebraska Medicaid",
      "tradingParterId": "medicaid_ne"
    },
    {
      "id": "357",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Neighborhood Health (Now AllWays Health Partners)",
      "tradingParterId": "neighborhood_health_plan"
    },
    {
      "id": "358",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Network Health",
      "tradingParterId": "network_health"
    },
    {
      "id": "359",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Network Health Plan",
      "tradingParterId": "network_health_plan"
    },
    {
      "id": "360",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Nevada Medicaid",
      "tradingParterId": "medicaid_nv"
    },
    {
      "id": "361",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "New Era Life Insurance Company",
      "tradingParterId": "new_era_life"
    },
    {
      "id": "362",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "New Hampshire Healthy Families",
      "tradingParterId": "new_hampshire_healthy_families"
    },
    {
      "id": "363",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "New Hampshire Medicaid",
      "tradingParterId": "medicaid_nh"
    },
    {
      "id": "364",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "New Jersey Medicaid",
      "tradingParterId": "medicaid_nj"
    },
    {
      "id": "365",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "New Mexico Medicaid",
      "tradingParterId": "medicaid_nm"
    },
    {
      "id": "366",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "New York Medicaid",
      "tradingParterId": "medicaid_ny"
    },
    {
      "id": "367",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Nippon Life (Principal Financial Group)",
      "tradingParterId": "nippon_life_principal"
    },
    {
      "id": "368",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "North Carolina Medicaid",
      "tradingParterId": "medicaid_nc"
    },
    {
      "id": "369",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "North Dakota Medicaid",
      "tradingParterId": "medicaid_nd"
    },
    {
      "id": "370",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Nova Healthcare Administrators",
      "tradingParterId": "nova_healthcare_administrators"
    },
    {
      "id": "371",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "NovaSys Health",
      "tradingParterId": "novasys_health"
    },
    {
      "id": "372",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Ohana Health Plan",
      "tradingParterId": "ohana_health_plan"
    },
    {
      "id": "373",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Ohio Medicaid",
      "tradingParterId": "medicaid_oh"
    },
    {
      "id": "374",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Oklahoma Health Care Authority (Oklahoma Medicaid)",
      "tradingParterId": "medicaid_ok"
    },
    {
      "id": "375",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Optima Health",
      "tradingParterId": "optima_health_plan"
    },
    {
      "id": "376",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "OptumHealth Behavioral Solutions",
      "tradingParterId": "optum_health_behavioral"
    },
    {
      "id": "377",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Oregon Medicaid",
      "tradingParterId": "medicaid_or"
    },
    {
      "id": "378",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Oscar Health Plan",
      "tradingParterId": "oscar_health_plan"
    },
    {
      "id": "379",
      "phxPayerId": "OXFHP",
      "payerName": "Other",
      "displayName": "Oxford Health Plan",
      "tradingParterId": ""
    },
    {
      "id": "380",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "PacificSource Health Plans",
      "tradingParterId": "pacific_source_health_plans"
    },
    {
      "id": "381",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Pan-American Life Insurance",
      "tradingParterId": "pan_american_life"
    },
    {
      "id": "382",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Paramount",
      "tradingParterId": "paramount"
    },
    {
      "id": "383",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Parkland Community Health Plan",
      "tradingParterId": "parkland_community"
    },
    {
      "id": "384",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Passport Health Plan",
      "tradingParterId": "passport_health_plan"
    },
    {
      "id": "385",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Peach State Health Plan",
      "tradingParterId": "peach_state_health"
    },
    {
      "id": "386",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "PEHP Utah",
      "tradingParterId": "pehp_ut"
    },
    {
      "id": "387",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Pennsylvania Health and Wellness",
      "tradingParterId": "pennsylvania_health_wellness"
    },
    {
      "id": "388",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Pennsylvania Medicaid",
      "tradingParterId": "medicaid_pa"
    },
    {
      "id": "389",
      "phxPayerId": "PCPIL",
      "payerName": "Other",
      "displayName": "Personal Care Insurance",
      "tradingParterId": ""
    },
    {
      "id": "390",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Physicians Health Plan of Mid-Michigan",
      "tradingParterId": "physicians_health_plan_of_mid_michigan"
    },
    {
      "id": "391",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Physicians Health Plan of Northern Indiana",
      "tradingParterId": "physicians_health_plan_northern_in"
    },
    {
      "id": "392",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Physicians Mutual Insurance Company",
      "tradingParterId": "physicians_mutual"
    },
    {
      "id": "394",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Planned Administrators Inc",
      "tradingParterId": "planned_admin_inc"
    },
    {
      "id": "395",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "PreferredOne",
      "tradingParterId": "preferredone_health_insurance"
    },
    {
      "id": "396",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Prestige Health Choice",
      "tradingParterId": "prestige_health_choice"
    },
    {
      "id": "397",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Prevea360 Health Plan",
      "tradingParterId": "prevea360_health_plan"
    },
    {
      "id": "398",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Principal Financial Group",
      "tradingParterId": "principal_financial"
    },
    {
      "id": "399",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Priority Health",
      "tradingParterId": "priority_health"
    },
    {
      "id": "400",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Providence Health Plan",
      "tradingParterId": "providence_health_plan"
    },
    {
      "id": "401",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "QualCare",
      "tradingParterId": "qualcare"
    },
    {
      "id": "402",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "QualChoice of Arkansas",
      "tradingParterId": "qual_choice_ar"
    },
    {
      "id": "403",
      "phxPayerId": "R2MSC",
      "payerName": "Blue Cross and Blue Shield",
      "displayName": "Regence Group Adminstrators",
      "tradingParterId": "regence_group_administrators"
    },
    {
      "id": "404",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Reliance Standard Life Insurance",
      "tradingParterId": "reliance_standard_life_insurance"
    },
    {
      "id": "405",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Reserve National Insurance Company",
      "tradingParterId": "reserve_national_insurance_company"
    },
    {
      "id": "406",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Rocky Mountain Health Plan",
      "tradingParterId": "rocky_mountain_health_plan"
    },
    {
      "id": "407",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "SAMBA (Special Agents Mutual Benefit Association)",
      "tradingParterId": "samba"
    },
    {
      "id": "408",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "San Francisco Health Plan (SFHP)",
      "tradingParterId": "san_francisco_health_plan"
    },
    {
      "id": "409",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Sanford Health Plan",
      "tradingParterId": "sanford_health_plan"
    },
    {
      "id": "410",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Santa Clara Family Health Plan",
      "tradingParterId": "santa_clara_family_health"
    },
    {
      "id": "411",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "SCAN Health Plan",
      "tradingParterId": "scan_health"
    },
    {
      "id": "412",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Scott and White Health Plan",
      "tradingParterId": "scott_and_white_health_plan"
    },
    {
      "id": "413",
      "phxPayerId": "UHCMC",
      "payerName": "Medicare",
      "displayName": "Secure Horizons Medicare Complete (United)",
      "tradingParterId": ""
    },
    {
      "id": "414",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Security Health Plan",
      "tradingParterId": "security_health_plan"
    },
    {
      "id": "415",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "SelectHealth",
      "tradingParterId": "selecthealth"
    },
    {
      "id": "416",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "SelectHealth of South Carolina",
      "tradingParterId": "selecthealth_sc"
    },
    {
      "id": "417",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Senior Whole Health",
      "tradingParterId": "senior_whole_health"
    },
    {
      "id": "418",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Sharp Health Plan",
      "tradingParterId": "sharp_health_plan"
    },
    {
      "id": "419",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Sierra Health Plan",
      "tradingParterId": "sierra_health_plan"
    },
    {
      "id": "420",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Simply Healthcare",
      "tradingParterId": "simply_healthcare"
    },
    {
      "id": "421",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "South Carolina Medicaid (Healthy Connections)",
      "tradingParterId": "medicaid_sc"
    },
    {
      "id": "422",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "South Dakota Medicaid",
      "tradingParterId": "medicaid_sd"
    },
    {
      "id": "423",
      "phxPayerId": "SHPPO",
      "payerName": "Other",
      "displayName": "Southern Health",
      "tradingParterId": ""
    },
    {
      "id": "424",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Standard Life and Accident Insurance Company",
      "tradingParterId": "standard_life"
    },
    {
      "id": "425",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "State Farm",
      "tradingParterId": "state_farm"
    },
    {
      "id": "426",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "StayWell",
      "tradingParterId": "staywell"
    },
    {
      "id": "427",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "SummaCare",
      "tradingParterId": "summacare"
    },
    {
      "id": "428",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Sunflower State Health Plan",
      "tradingParterId": "sunflower_state_health_plan"
    },
    {
      "id": "429",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Sunshine State Health Plan",
      "tradingParterId": "sunshine_state_health_plan"
    },
    {
      "id": "430",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Superior Health Plan",
      "tradingParterId": "superior_health_plan"
    },
    {
      "id": "431",
      "phxPayerId": "THCTN",
      "payerName": "Other",
      "displayName": "Tennessee Healthcare",
      "tradingParterId": ""
    },
    {
      "id": "432",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Tennessee Medicaid (TennCare)",
      "tradingParterId": "medicaid_tn"
    },
    {
      "id": "433",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Texas Medicaid",
      "tradingParterId": "medicaid_tx_acute_care"
    },
    {
      "id": "434",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "The Health Plan",
      "tradingParterId": "the_health_plan"
    },
    {
      "id": "435",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Today's Options",
      "tradingParterId": "todays_options"
    },
    {
      "id": "436",
      "phxPayerId": "R2MSC",
      "payerName": "Tricare",
      "displayName": "Tricare East",
      "tradingParterId": "tricare_east"
    },
    {
      "id": "437",
      "phxPayerId": "R2MSC",
      "payerName": "Tricare",
      "displayName": "Tricare for Life",
      "tradingParterId": "tricare_for_life"
    },
    {
      "id": "438",
      "phxPayerId": "R2MSC",
      "payerName": "Tricare",
      "displayName": "Tricare Overseas",
      "tradingParterId": "tricare_overseas"
    },
    {
      "id": "439",
      "phxPayerId": "R2MSC",
      "payerName": "Tricare",
      "displayName": "Tricare West",
      "tradingParterId": "tricare_west"
    },
    {
      "id": "440",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Trillium Community Health Plan",
      "tradingParterId": "trillium_health_plan"
    },
    {
      "id": "441",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Trustmark",
      "tradingParterId": "trustmark"
    },
    {
      "id": "442",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Tufts Health Plan",
      "tradingParterId": "tufts"
    },
    {
      "id": "443",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "UCare of Minnesota",
      "tradingParterId": "ucare"
    },
    {
      "id": "444",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "UHC West (PacifiCare)",
      "tradingParterId": "uhc_west"
    },
    {
      "id": "445",
      "phxPayerId": "UMRFS",
      "payerName": "UMR",
      "displayName": "UMR",
      "tradingParterId": ""
    },
    {
      "id": "446",
      "phxPayerId": "UNICA",
      "payerName": "UniCare",
      "displayName": "UNICARE",
      "tradingParterId": ""
    },
    {
      "id": "447",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "United American Insurance Company",
      "tradingParterId": "united_american_insurance_company"
    },
    {
      "id": "448",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Health Care Shared Services",
      "tradingParterId": "united_health_care_shared_services"
    },
    {
      "id": "449",
      "phxPayerId": "UNITE",
      "phoenix_payer_id": "UNITE",
      "tp_name": "UHC",
      "tp_id": "441",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare",
      "tradingParterId": ""
    },
    {
      "id": "450",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Community Plan (AmeriChoice)",
      "tradingParterId": "united_health_care_community_plan"
    },
    {
      "id": "451",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Community Plan of Kansas (KanCare)",
      "tradingParterId": "united_health_care_community_plan_ks"
    },
    {
      "id": "452",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Community Plan of Michigan",
      "tradingParterId": "united_health_care_community_plan_mi"
    },
    {
      "id": "453",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Community Plan of New Jersey (AmeriChoice)",
      "tradingParterId": "united_health_care_community_plan_nj"
    },
    {
      "id": "454",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Community Plan of Tennessee (TennCare)",
      "tradingParterId": "united_health_care_community_plan_tn"
    },
    {
      "id": "455",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Community Plan PA (AmeriChoice)",
      "tradingParterId": "united_health_care_community_plan_pa"
    },
    {
      "id": "456",
      "phxPayerId": "EMPME",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Empire Plan",
      "tradingParterId": ""
    },
    {
      "id": "457",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Facets Detroit",
      "tradingParterId": "united_health_care_facets_detroit"
    },
    {
      "id": "458",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Facets Pittsburgh",
      "tradingParterId": "united_health_care_facets_pittsburgh"
    },
    {
      "id": "459",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Medicare Solutions (Evercare)",
      "tradingParterId": "united_health_care_medicare_solutions"
    },
    {
      "id": "460",
      "phxPayerId": "R2MSC",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare of the River Valley",
      "tradingParterId": "united_health_care_river_valley"
    },
    {
      "id": "461",
      "phxPayerId": "SIMEG",
      "payerName": "United Healthcare",
      "displayName": "United Healthcare Student Resources (Student Insurance)",
      "tradingParterId": ""
    },
    {
      "id": "462",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Quartz Health Benefits Plans Corporation",
      "tradingParterId": "unity_health"
    },
    {
      "id": "463",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Univera Healthcare",
      "tradingParterId": "univera"
    },
    {
      "id": "464",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "University of Arizona Health Plans",
      "tradingParterId": "university_healthcare_marketplace"
    },
    {
      "id": "465",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "University of Missouri Health (Coventry)",
      "tradingParterId": "university_mo_coventry"
    },
    {
      "id": "466",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "University of Utah Health Plans",
      "tradingParterId": "university_of_utah"
    },
    {
      "id": "467",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "University Physicians Care Advantage (AZ)",
      "tradingParterId": "university_physicians_care_advantage_az"
    },
    {
      "id": "468",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "UPMC Health Plan",
      "tradingParterId": "upmc_health_plan"
    },
    {
      "id": "469",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "USAA Life Insurance",
      "tradingParterId": "usaa_life"
    },
    {
      "id": "470",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Utah Medicaid",
      "tradingParterId": "medicaid_ut"
    },
    {
      "id": "471",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "VA Fee Basis Program",
      "tradingParterId": "va_fee_basis"
    },
    {
      "id": "472",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Vermont Medicaid",
      "tradingParterId": "medicaid_vt"
    },
    {
      "id": "473",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Veterans Affairs Health Administration Center",
      "tradingParterId": "va_health_administration"
    },
    {
      "id": "474",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Virginia Medicaid",
      "tradingParterId": "medicaid_va"
    },
    {
      "id": "475",
      "phxPayerId": "VHPPO",
      "payerName": "Other",
      "displayName": "Vista Health Plan",
      "tradingParterId": ""
    },
    {
      "id": "476",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Viva Health",
      "tradingParterId": "viva_health"
    },
    {
      "id": "477",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "VNS Choice",
      "tradingParterId": "vns_choice"
    },
    {
      "id": "478",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Washington Medicaid",
      "tradingParterId": "medicaid_wa"
    },
    {
      "id": "479",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "WEA Trust",
      "tradingParterId": "wea_trust"
    },
    {
      "id": "480",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "WebTPA",
      "tradingParterId": "web_tpa"
    },
    {
      "id": "481",
      "phxPayerId": "R2MSC",
      "payerName": "Wellcare",
      "displayName": "Wellcare",
      "tradingParterId": "wellcare"
    },
    {
      "id": "482",
      "phxPayerId": "WPSNC",
      "payerName": "Other",
      "displayName": "Wellpath Select HMO",
      "tradingParterId": ""
    },
    {
      "id": "483",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "West Virginia Medicaid",
      "tradingParterId": "medicaid_wv"
    },
    {
      "id": "484",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Western Health Advantage",
      "tradingParterId": "western_health_advantage"
    },
    {
      "id": "485",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Wisconsin Medicaid",
      "tradingParterId": "medicaid_wi"
    },
    {
      "id": "486",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "WPS Insurance",
      "tradingParterId": "wps_insurance"
    },
    {
      "id": "487",
      "phxPayerId": "R2MSC",
      "payerName": "Other",
      "displayName": "Writers Guild",
      "tradingParterId": "writers_guild"
    },
    {
      "id": "488",
      "phxPayerId": "R2MSC",
      "payerName": "Medicaid",
      "displayName": "Wyoming Medicaid",
      "tradingParterId": "medicaid_wy"
    }
  ],
  sortPayers(){
    var payers = this.sortByKey(this.payersCompiled, 'displayName');
    payers.unshift(
      {
          "id": 'I don\'t have insurance',
          "phxPayerId": '0', 
          "payerName": 'I don\'t have insurance',
          "displayName": 'I don\'t have insurance',
          "tradingParterId": 'I don\'t have insurance'
      },
      {
          "id": 'I don\'t see my insurance',
          "phxPayerId": '0', 
          "payerName": 'I don\'t see my insurance',
          "displayName": 'I don\'t see my insurance',
          "tradingParterId": 'I don\'t see my insurance',
      }
    );
    return payers

  },
  sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
  }
}
export default payerInfo;
// export default {
    
// };
