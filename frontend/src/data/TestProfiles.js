// Test profiles for quickly loading different user scenarios
export const testProfiles = {
  julia_default: {
    name: "Anna - Default Test Profile",
    personalInfo: {
      Member_FirstName: "Anna",
      Member_LastName: "Boegeman",
      Member_BirthDate: "04/24/1994",
      Member_Gender: "Female",
      Member_State: "",
      Member_PrimaryPhone: "8588378077",
      Member_PrimaryPhoneType: "",
      ConsentToLeaveMessage: false,
      ConsentToContact: false,
      Member_Email: "test@labcorp.com",
      Member_Identifier: "Patient",
      CallbackSelection: ""
    },
    insuranceInfo: {
      insuranceProvider: "1",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "771901836381",
      Group_Number: "",
      Member_State: "",
      DueDate: "2026-01-18",
      isPregnant: true,
      NumberOfFetuses: "1"
    },
    testSelection: {
      phoenixTestId: 6,
      CurrentTestName: "MaterniT 21 PLUS"
    }
  },
  
  sarah_aetna: {
    name: "Sarah - Aetna Coverage",
    personalInfo: {
      Member_FirstName: "Sarah",
      Member_LastName: "Johnson",
      Member_BirthDate: "05/20/1988",
      Member_Gender: "Female",
      Member_State: "CA",
      Member_PrimaryPhone: "5551234567",
      Member_PrimaryPhoneType: "Mobile",
      ConsentToLeaveMessage: true,
      ConsentToContact: true,
      Member_Email: "sarah.j@example.com",
      Member_Identifier: "Patient",
      CallbackSelection: "Morning"
    },
    insuranceInfo: {
      insuranceProvider: "2",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "AET123456789",
      Group_Number: "GRP001",
      Member_State: "CA",
      DueDate: "2026-03-15",
      isPregnant: true,
      NumberOfFetuses: "1"
    },
    testSelection: {
      phoenixTestId: 6,
      CurrentTestName: "MaterniT 21 PLUS"
    }
  },
  
  emily_bcbs: {
    name: "Emily - Blue Cross Blue Shield",
    personalInfo: {
      Member_FirstName: "Emily",
      Member_LastName: "Martinez",
      Member_BirthDate: "11/08/1992",
      Member_Gender: "Female",
      Member_State: "TX",
      Member_PrimaryPhone: "5559876543",
      Member_PrimaryPhoneType: "Mobile",
      ConsentToLeaveMessage: true,
      ConsentToContact: false,
      Member_Email: "emily.m@example.com",
      Member_Identifier: "Patient",
      CallbackSelection: "Afternoon"
    },
    insuranceInfo: {
      insuranceProvider: "3",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "BCBS987654321",
      Group_Number: "GRP002",
      Member_State: "TX",
      DueDate: "2026-04-22",
      isPregnant: true,
      NumberOfFetuses: "2"
    },
    testSelection: {
      phoenixTestId: 6,
      CurrentTestName: "MaterniT 21 PLUS"
    }
  },
  
  kelly_noble_uhc: {
    name: "Kelly Noble - United Healthcare",
    personalInfo: {
      Member_FirstName: "Kelly",
      Member_LastName: "Noble",
      Member_BirthDate: "11/13/1989",
      Member_Gender: "Female",
      Member_State: "",
      Member_PrimaryPhone: "5551234567",
      Member_PrimaryPhoneType: "Mobile",
      ConsentToLeaveMessage: true,
      ConsentToContact: true,
      Member_Email: "kelly.noble@example.com",
      Member_Identifier: "Patient",
      CallbackSelection: ""
    },
    insuranceInfo: {
      insuranceProvider: "449",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "983200549",
      Group_Number: "",
      Member_State: "",
      DueDate: "2026-06-15",
      isPregnant: true,
      NumberOfFetuses: "1"
    },
    testSelection: {
      phoenixTestId: 6,
      CurrentTestName: "MaterniT 21 PLUS"
    }
  },
  
  anna_boegeman_uhc: {
    name: "Anna Boegeman - United Healthcare",
    personalInfo: {
      Member_FirstName: "Anna",
      Member_LastName: "Boegeman",
      Member_BirthDate: "04/24/1994",
      Member_Gender: "Female",
      Member_State: "",
      Member_PrimaryPhone: "5559876543",
      Member_PrimaryPhoneType: "Mobile",
      ConsentToLeaveMessage: true,
      ConsentToContact: true,
      Member_Email: "anna.boegeman@example.com",
      Member_Identifier: "Patient",
      CallbackSelection: ""
    },
    insuranceInfo: {
      insuranceProvider: "449",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "771901836381",
      Group_Number: "",
      Member_State: "",
      DueDate: "2026-07-20",
      isPregnant: true,
      NumberOfFetuses: "1"
    },
    testSelection: {
      phoenixTestId: 6,
      CurrentTestName: "MaterniT 21 PLUS"
    }
  },
  
  briana_freeman_uhc: {
    name: "Briana Freeman - United Healthcare",
    personalInfo: {
      Member_FirstName: "Briana",
      Member_LastName: "Freeman",
      Member_BirthDate: "03/06/2002",
      Member_Gender: "Female",
      Member_State: "",
      Member_PrimaryPhone: "5551234567",
      Member_PrimaryPhoneType: "Mobile",
      ConsentToLeaveMessage: true,
      ConsentToContact: true,
      Member_Email: "briana.freeman@example.com",
      Member_Identifier: "Patient",
      CallbackSelection: ""
    },
    insuranceInfo: {
      insuranceProvider: "449",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "28660521302",
      Group_Number: "",
      Member_State: "",
      DueDate: "2026-08-10",
      isPregnant: true,
      NumberOfFetuses: "1"
    },
    testSelection: {
      phoenixTestId: 6,
      CurrentTestName: "MaterniT 21 PLUS"
    }
  },
  
  custom_template: {
    name: "Custom - Empty Template",
    personalInfo: {
      Member_FirstName: "",
      Member_LastName: "",
      Member_BirthDate: "",
      Member_Gender: "",
      Member_State: "",
      Member_PrimaryPhone: "",
      Member_PrimaryPhoneType: "",
      ConsentToLeaveMessage: false,
      ConsentToContact: false,
      Member_Email: "",
      Member_Identifier: "Patient",
      CallbackSelection: ""
    },
    insuranceInfo: {
      insuranceProvider: "",
      TradingPartnerId: { key: "", value: "" },
      Member_Id: "",
      Group_Number: "",
      Member_State: "",
      DueDate: "",
      isPregnant: false,
      NumberOfFetuses: "1"
    },
    testSelection: {
      phoenixTestId: "",
      CurrentTestName: ""
    }
  }
};

export default testProfiles;
