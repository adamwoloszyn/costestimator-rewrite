// Note: imageUrl paths are relative and will be prefixed with IMAGE_SOURCE from environment config
export default {
//     informaSeq or MaterniT21 PLUS you will pass CPT 81420
// Inheritest Ashkenazi Jewish or Inheritest Society-Guided = CPT 81412
// MaterniT21 Genome =  CPT bundle 81420, 81422, 81479
// Inheritest Comprehensive = CPT 81223

    staticTestCategories: [
    {
        "displayName": "Pre-pregnancy",
        "description": "Carrier screening to help detect the risk of having a baby with a specific inherited disorder, such as cystic fibrosis",
        "imageUrl": "/assets/images/PrePregnancy@3x-8.png",
        "color": "blue",
        "tests":[ 
            {
                "id" : 11,
                "parentIndex": 0,
                "indexInDynamicData": 1,
                "testCode": "81443, 81243",
                "categoryName": "Inheritest",
                "testName": "500 PLUS Panel",
                "displayName": "500 PLUS Panel",
                "headerDisplay": "Inheritest 500 PLUS Panel",
                "description": "Carrier screening of more than 500 genes associated with many genetic disorders. Can be performed during and before pregnancy. Not available in New York State.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['500 PLUS Panel']
            },
            {
                "id" : 15,
                "parentIndex": 0,
                "indexInDynamicData": 8,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "300 PLUS Panel",
                "displayName": "300 PLUS Panel",
                "headerDisplay": "Inheritest 300 PLUS Panel",
                "description": "Carrier screening for more than 300 high-frequency genetics disorders. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['300 PLUS Panel']
            }, 
            {
                "id" : 16,
                "parentIndex": 0,
                "indexInDynamicData": 9,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "100 PLUS Panel",
                "displayName": "100 PLUS Panel",
                "headerDisplay": "Inheritest 100 PLUS Panel",
                "description": "Carrier screening for more than 100 genetic disorders. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['100 PLUS Panel']
            },  
            {
                "id" : 14,
                "parentIndex": 0,
                "indexInDynamicData": 10,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "High Frequency Panel",
                "displayName": "High Frequency Panel",
                "headerDisplay": "Inheritest High Frequency Panel",
                "description": "Carrier screening covering 111 high-frequency genetic disorders included in the medical society recommendations. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['High Frequency Panel']
            }, 
             {
                "id" : 17,
                "parentIndex": 0,
                "indexInDynamicData": 11,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "14-gene Panel",
                "displayName": "14-gene Panel",
                "headerDisplay": "Inheritest 14-gene Panel",
                "description": "Carrier screening for 14 genes associated with more than 13 genetic disorders specifically chosen due to their frequency in specific, high-risk ethnic groups. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['14-gene Panel']
            }, 
            {
                "id" : 9,
                "parentIndex": 0,
                "indexInDynamicData": 4,
                "testCode": "81220, 81329, 81243",
                "categoryName": "Inheritest",
                "testName": "Core Panel",
                "displayName": "Core Panel",
                "headerDisplay": "Inheritest Core Panel",
                "description": "Carrier screening for some of the most common and severe genetic disorders: cystic fibrosis (CF), spinal muscular atrophy (SMA) and fragile X syndrome. Can be performed during and before pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['Core Panel']
            },
            {
                "id" : 12,
                "parentIndex": 0,
                "indexInDynamicData": 5,
                "testCode": "81220",
                "categoryName": "Inheritest",
                "testName": "CF/SMA Panel",
                "displayName": "CF/SMA Panel",
                "headerDisplay": "Inheritest CF/SMA Panel",
                "description": "Carrier screening for some of the most common and severe genetic disorders: cystic fibrosis (CF) and spinal muscular atrophy (SMA). Can be performed during and before pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['CF/SMA Panel']
            },  
            {
                "id" : 5,
                "parentIndex": 0,
                "indexInDynamicData": 11,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "14-gene Panel",
                "displayName": "I am not sure which carrier screening option is right for me.",
                "headerDisplay": "Inheritest 14-gene Panel",
                "description": "We will help you choose the most appropriate test for your needs.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['Inheritest - Not Sure']
            }
        ]
    },
    {
        "displayName": "Pregnancy",
        "description": "Multiple testing options providing information on the genetic health of your baby during the first and second trimesters",
        "imageUrl": "/assets/images/Pregnancy_1@3x-8.png",
        "color": "blue",
        "tests":[ 
            {
                "id" : 11,
                "parentIndex": 0,
                "indexInDynamicData": 1,
                "testCode": "81443, 81243",
                "categoryName": "Inheritest",
                "testName": "500 PLUS Panel",
                "displayName": "500 PLUS Panel",
                "headerDisplay": "Inheritest 500 PLUS Panel",
                "description": "Carrier screening of more than 500 genes associated with many genetic disorders. Can be performed during and before pregnancy. Not available in New York State.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['500 PLUS Panel']
            },
            {
                "id" : 15,
                "parentIndex": 0,
                "indexInDynamicData": 8,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "300 PLUS Panel",
                "displayName": "300 PLUS Panel",
                "headerDisplay": "Inheritest 300 PLUS Panel",
                "description": "Carrier screening for more than 300 high-frequency genetics disorders. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['300 PLUS Panel']
            }, 
            {
                "id" : 16,
                "parentIndex": 0,
                "indexInDynamicData": 9,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "100 PLUS Panel",
                "displayName": "100 PLUS Panel",
                "headerDisplay": "Inheritest 100 PLUS Panel",
                "description": "Carrier screening for more than 100 genetic disorders. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['100 PLUS Panel']
            }, 
            {
                "id" : 14,
                "parentIndex": 0,
                "indexInDynamicData": 10,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "High Frequency Panel",
                "displayName": "High Frequency Panel",
                "headerDisplay": "Inheritest High Frequency Panel",
                "description": "Carrier screening covering 111 high-frequency genetic disorders included in the medical society recommendations. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['High Frequency Panel']
            }, 
            {
                "id" : 17,
                "parentIndex": 0,
                "indexInDynamicData": 11,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "14-gene Panel",
                "displayName": "14-gene Panel",
                "headerDisplay": "Inheritest 14-gene Panel",
                "description": "Carrier screening for 14 genes associated with more than 13 genetic disorders specifically chosen due to their frequency in specific, high-risk ethnic groups. It can be performed before or during pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['14-gene Panel']
            }, 
            {
                "id" : 9,
                "parentIndex": 0,
                "indexInDynamicData": 4,
                "testCode": "81220, 81329, 81243",
                "categoryName": "Inheritest",
                "testName": "Core Panel",
                "displayName": "Core Panel",
                "headerDisplay": "Inheritest Core Panel",
                "description": "Carrier screening for some of the most common and severe genetic disorders: cystic fibrosis (CF), spinal muscular atrophy (SMA) and fragile X syndrome. Can be performed during and before pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['Core Panel']
            }, 
            {
                "id" : 12,
                "parentIndex": 0,
                "indexInDynamicData": 5,
                "testCode": "81220",
                "categoryName": "Inheritest",
                "testName": "CF/SMA Panel",
                "displayName": "CF/SMA Panel",
                "headerDisplay": "Inheritest CF/SMA Panel",
                "description": "Carrier screening for some of the most common and severe genetic disorders: cystic fibrosis (CF) and spinal muscular atrophy (SMA). Can be performed during and before pregnancy.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['CF/SMA Panel']
            }, 
            {
                "id" : 10,
                "parentIndex": 0,
                "indexInDynamicData": 7,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "GeneSeq PLUS",
                "displayName": "GeneSeq PLUS",
                "headerDisplay": "Inheritest GeneSeq PLUS",
                "description": "Detailed sequencing/analysis of a particular gene from the Inheritest 500 PLUS panel. Not available in New York State.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['GeneSeq PLUS']
            },
            {
                "id" : 6,
                "parentIndex": 1,
                "indexInDynamicData": 0,
                "testCode": "81420",
                "categoryName": "NIPT",
                "testName": "MaterniT 21 PLUS",
                "displayName": "MaterniT 21 PLUS",
                "headerDisplay": "MaterniT 21 PLUS",
                "description": "A prenatal test that screens for many genetic abnormalities, including trisomy 21 (Down syndrome), trisomy 18 (Edwards syndrome), and trisomy 13 (Patau syndrome).  It can also tell you if your baby is a boy or a girl.",
                "imageUrl": "/assets/images/maternit_21_plus.svg",
                "isActive": window.tests['MaterniT 21 PLUS']
            }, 
            {
                "id" : 1,
                "parentIndex": 2,
                "indexInDynamicData": 0,
                "testCode": "81420",
                "categoryName": "NIPT",
                "testName": "informaSeq",
                "displayName": "informaSeq",
                "headerDisplay": "informaSeq",
                "description": "An early and accurate test for assessing the risk of Down syndrome and other chromosome conditions.",
                "imageUrl": "/assets/images/informaSeq.svg",
                "isActive": window.tests['informaSeq']
            }, 
            {
                "id" : 7,
                "parentIndex": 1,
                "indexInDynamicData": 1,
                "testCode": "81420, 81422, 81479",
                "categoryName": "NIPT",
                "testName": "MaterniT GENOME",
                "displayName": "MaterniT GENOME",
                "headerDisplay": "MaterniT GENOME",
                "description": "The only prenatal test on the market that screens for the widest range of genetic chromosomal abnormalities.  It can also tell you if your baby is a boy or a girl.",
                "imageUrl": "/assets/images/maternit_genome.svg",
                "isActive": window.tests['MaterniT GENOME']
            }, 
            {
                "id" : 8,
                "parentIndex": 1,
                "indexInDynamicData": 2,
                "testCode": "81420",
                "categoryName": "NIPT",
                "testName": "MaterniT 21 PLUS",
                "displayName": "I am not sure which noninvasive screening test option is right for me",
                "headerDisplay": "MaterniT 21 PLUS",
                "description": "We will help you choose the most appropriate test for your needs.",
                "imageUrl": "/assets/images/icn_pregnancy.svg",
                "isActive": window.tests['NIPT - Not Sure']
            },
            {
                "id" : 5,
                "parentIndex": 0,
                "indexInDynamicData": 11,
                "testCode": "81223",
                "categoryName": "Inheritest",
                "testName": "14-gene Panel",
                "displayName": "I am not sure which carrier screening option is right for me.",
                "headerDisplay": "Inheritest 14-gene Panel",
                "description": "We will help you choose the most appropriate test for your needs.",
                "imageUrl": "/assets/images/inheritest.svg",
                "isActive": window.tests['Inheritest - Not Sure']
            }
        ]
    },
    ]
};


  //old implementation
  // selectTest = (path, index) => {
  //   this.setState({ 
  //     selectedTestCategoryName: this.state.staticTestCategories[this.state.testCategorySelectedIndex].displayName,
  //     selectedTestName: this.state.currentTestList[index].displayName,
  //     currentTestQuestions: this.state.currentTestList[index].questions,
  //     testSelectedIndex: index,
  //     currentStep: this.state.currentStep+1,
  //     currentStepRoute: '/insuranceInformation'
  //    });
  //   this.nextPath(path);
  // }