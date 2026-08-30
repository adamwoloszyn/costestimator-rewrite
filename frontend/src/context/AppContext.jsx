import { createContext, useContext, useState, useEffect, useCallback } from 'react';
import StaticCustomerObject from '../data/StaticCustomerObject';
import StaticPayerObject from '../data/StaticPayerObject';
import api from '../utils/api';

const AppContext = createContext();

export function AppProvider({ children }) {
  const [state, setState] = useState({
    // Token and version
    token: '',
    id: '',
    version_number: '',
    
    // Banner and messaging
    bannerMessage: '',
    
    // Test data
    phoenixTestId: '',
    CurrentTestName: '',
    testCategories: [],
    staticTestCategories: [],
    currentTestCode: '',
    currentTestIndex: '',
    currentParentIndex: '',
    currentTestList: [],
    currentTestQuestions: [],
    testCategorySelectedIndex: 0,
    testSelectedIndex: 0,
    selectedTestCategoryName: '',
    actualTestCategoryName: '',
    selectedTestName: '',
    selectedTestNameForSF: '',
    
    // Insurance data
    insuranceProviders: [],
    insuranceStates: StaticPayerObject.insuranceStates || [],
    
    // Navigation and flow
    currentStep: 0,
    currentStepButtonLabel: 'Continue',
    goingBackwards: false,
    
    // Captcha
    CaptchaSiteKey: '',
    
    // User type
    isBillingTeamMember: false,
    
    // Estimate attempts
    estimateAttempts: 1,
    
    // Personal Info - initialize with static data
    personalInfoObject: {
      'Member_FirstName': StaticCustomerObject.user['Member_FirstName'],
      'Member_LastName': StaticCustomerObject.user['Member_LastName'],
      'Member_BirthDate': StaticCustomerObject.user['Member_BirthDate'],
      'Member_Gender': StaticCustomerObject.user['Member_Gender'],
      'Member_State': StaticCustomerObject.user['Member_State'],
      'Member_PrimaryPhone': StaticCustomerObject.user['Member_PrimaryPhone'],
      'Member_PrimaryPhoneType': StaticCustomerObject.user['Member_PrimaryPhoneType'],
      'ConsentToLeaveMessage': StaticCustomerObject.user['ConsentToLeaveMessage'],
      'ConsentToContact': StaticCustomerObject.user['ConsentToContact'],
      'Member_Email': StaticCustomerObject.user['Member_Email'],
      'Member_Identifier': StaticCustomerObject.user['Member_Identifier'],
      'CallbackSelection': StaticCustomerObject.user['CallbackSelection']
    },
    
    // Insurance Info - initialize with static data
    insuranceInfoObject: {
      'insuranceProvider': StaticCustomerObject.user['insuranceProvider'],
      'TradingPartnerId': StaticCustomerObject.user['TradingPartnerId'],
      'Member_Id': StaticCustomerObject.user['Member_Id'],
      'Group_Number': StaticCustomerObject.user['Group_Number'],
      'DueDate': StaticCustomerObject.user['DueDate'],
      'isPregnant': StaticCustomerObject.user['isPregnant'],
      'NumberOfFetuses': StaticCustomerObject.user['NumberOfFetuses']
    },
    
    // Estimate Info
    estimateInfoObject: {
      'testName': '',
      'phoenixTestId': '',
      'estimateAttempts': 1,
      'isEstimatorAvailable': true,  // IMPORTANT: Default to true like original
      'isASAPAvailable': false,
      'receivedEstimate': false,
      'receivedSalesforceCaseNumber': false
    }
  });

  // Update specific parts of state
  const updateState = useCallback((updates) => {
    setState(prevState => ({
      ...prevState,
      ...updates
    }));
  }, []);

  // Update personalInfoObject
  const updatePersonalInfo = useCallback((field, value) => {
    setState(prevState => ({
      ...prevState,
      personalInfoObject: {
        ...prevState.personalInfoObject,
        [field]: value
      }
    }));
  }, []);

  // Update insuranceInfoObject
  const updateInsuranceInfo = useCallback((field, value) => {
    setState(prevState => ({
      ...prevState,
      insuranceInfoObject: {
        ...prevState.insuranceInfoObject,
        [field]: value
      }
    }));
  }, []);

  // Update estimateInfoObject
  const updateEstimateInfo = useCallback((field, value) => {
    setState(prevState => ({
      ...prevState,
      estimateInfoObject: {
        ...prevState.estimateInfoObject,
        [field]: value
      }
    }));
  }, []);

  const value = {
    state,
    setState,
    updateState,
    updatePersonalInfo,
    updateInsuranceInfo,
    updateEstimateInfo
  };

  return <AppContext.Provider value={value}>{children}</AppContext.Provider>;
}

export function useAppContext() {
  const context = useContext(AppContext);
  if (!context) {
    throw new Error('useAppContext must be used within AppProvider');
  }
  return context;
}

export default AppContext;
