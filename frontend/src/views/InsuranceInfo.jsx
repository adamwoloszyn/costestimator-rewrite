import { useState, useEffect } from 'react';
import Turnstile from 'react-turnstile';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import SingleInput from '../components/SingleInput';
import Button from '../components/Button';
import DynamicSelect from '../components/DynamicSelect';
import api from '../utils/api';

function InsuranceInfo() {
  const { state, updateInsuranceInfo, updateEstimateInfo, updateState } = useAppContext();
  const navigate = useNavigate();

  // Redirect if no personal info (user skipped previous steps)
  useEffect(() => {
    if (!state.personalInfoObject || !state.personalInfoObject.Member_FirstName) {
      console.log('No personal info, redirecting to home');
      navigate('/');
    }
  }, [state.personalInfoObject, navigate]);

  const [formState, setFormState] = useState({
    insuranceProvider: state.insuranceInfoObject.insuranceProvider || '',
    Member_Id: state.insuranceInfoObject.Member_Id || '',
    HighRisk_3: state.insuranceInfoObject.HighRisk_3 || '',
    DueDate: state.insuranceInfoObject.DueDate || '',
    MultiFetalGestation_8: state.insuranceInfoObject.MultiFetalGestation_8 || '',
    FamilyHistory_9: state.insuranceInfoObject.FamilyHistory_9 || '',
    insuranceTextFilter: '',
    insuranceStateSelection: '',
    isMember_Id_Enabled: state.insuranceInfoObject.isMember_Id_Enabled !== false
  });

  const [filteredProviders, setFilteredProviders] = useState(state.insuranceProviders || []);
  const [hasErrors, setHasErrors] = useState(false);
  const [recaptchaResponse, setRecaptchaResponse] = useState(null);
  
  // Handle Turnstile callback (matches old cost estimator)
  const handleTurnstileCallback = (token) => {
    console.log('Turnstile token:', token);
    setRecaptchaResponse(token);
  };

  const handleInputChange = (field, value) => {
    setFormState(prev => ({ ...prev, [field]: value }));
    updateInsuranceInfo(field, value);
  };

  const handleInsuranceProviderChange = (e) => {
    const value = e.target.value;
    const isNoInsurance = value === '1' || value === '2';
    setFormState(prev => ({
      ...prev,
      insuranceProvider: value,
      isMember_Id_Enabled: !isNoInsurance,
      Member_Id: isNoInsurance ? '' : prev.Member_Id
    }));
    updateInsuranceInfo('insuranceProvider', value);
    updateInsuranceInfo('isMember_Id_Enabled', !isNoInsurance);
    if (isNoInsurance) {
      updateInsuranceInfo('Member_Id', '');
    }
  };

  const handleTextFilterChange = (e) => {
    const value = e.target.value.toLowerCase();
    setFormState(prev => ({ ...prev, insuranceTextFilter: value }));

    // Filter providers based on text
    if (value === '') {
      setFilteredProviders(state.insuranceProviders);
    } else {
      const filtered = state.insuranceProviders.filter(provider => 
        provider.displayName.toLowerCase().indexOf(value) > -1
      );
      setFilteredProviders(filtered);
    }
  };

  const handleStateChange = (e) => {
    const stateValue = e.target.value;
    setFormState(prev => ({ ...prev, insuranceStateSelection: stateValue }));

    // Filter by both state and text
    let filtered = state.insuranceProviders;

    if (stateValue !== 'Select' || formState.insuranceTextFilter !== '') {
      filtered = state.insuranceProviders.filter(provider => {
        const stateMatch = stateValue === 'Select' || 
          (provider.state_coverage && provider.state_coverage.indexOf(stateValue) > -1);
        const textMatch = formState.insuranceTextFilter === '' || 
          provider.displayName.toLowerCase().indexOf(formState.insuranceTextFilter) > -1;
        
        return stateMatch && textMatch;
      });
    }

    setFilteredProviders(filtered);
  };

  const goBack = () => {
    navigate(-1);
  };

  const getQuestionOptionText = (questionId, answerValue) => {
    if (!answerValue) {
      return '';
    }

    const question = state.currentTestQuestions.find(
      currentQuestion => Number(currentQuestion.id) === Number(questionId)
    );

    if (!question || !Array.isArray(question.options)) {
      return '';
    }

    const selectedOption = question.options.find(option => {
      if (typeof option === 'string') {
        return option === answerValue;
      }

      return String(option.id) === String(answerValue);
    });

    if (!selectedOption) {
      return '';
    }

    return typeof selectedOption === 'string'
      ? selectedOption
      : (selectedOption.displayName || selectedOption.label || '');
  };

  const handleContinue = async () => {
    // Validate all required fields before proceeding
    let hasFormErrors = false;

    // Insurance provider
    const insuranceEl = document.querySelector('#insuranceProvider')?.parentNode;
    if (!formState.insuranceProvider || formState.insuranceProvider === 'Make a selection') {
      insuranceEl?.classList.add('error');
      hasFormErrors = true;
    } else {
      insuranceEl?.classList.remove('error');
    }

    // Member ID — required and alphanumeric (only when insurance is selected, not for no-insurance options)
    const memberIdEl = document.querySelector('#Member_Id');
    const letterNumber = /^[0-9a-zA-Z]+$/;
    if (formState.isMember_Id_Enabled) {
      if (!formState.Member_Id || formState.Member_Id.trim() === '') {
        memberIdEl?.classList.add('error');
        hasFormErrors = true;
      } else if (!formState.Member_Id.match(letterNumber)) {
        memberIdEl?.classList.add('error');
        hasFormErrors = true;
      } else {
        memberIdEl?.classList.remove('error');
      }
    } else {
      memberIdEl?.classList.remove('error');
    }

    // Turnstile / captcha
    const turnstileErrorEl = document.querySelector('#turnstileError');
    if (!recaptchaResponse) {
      turnstileErrorEl?.classList.remove('hidden');
      hasFormErrors = true;
    } else {
      turnstileErrorEl?.classList.add('hidden');
    }

    // Dynamic questions (HighRisk_3, MultiFetalGestation_8, FamilyHistory_9)
    state.currentTestQuestions?.forEach(question => {
      if (question.id === 3) {
        const el = document.querySelector('#HighRisk_3')?.parentNode;
        if (!formState.HighRisk_3) { el?.classList.add('error'); hasFormErrors = true; }
        else { el?.classList.remove('error'); }
      } else if (question.id === 8) {
        const el = document.querySelector('#MultiFetalGestation_8')?.parentNode;
        if (!formState.MultiFetalGestation_8) { el?.classList.add('error'); hasFormErrors = true; }
        else { el?.classList.remove('error'); }
      } else if (question.id === 9) {
        const el = document.querySelector('#FamilyHistory_9')?.parentNode;
        if (!formState.FamilyHistory_9) { el?.classList.add('error'); hasFormErrors = true; }
        else { el?.classList.remove('error'); }
      }
    });

    // DueDate — validate if filled in
    if (formState.DueDate) {
      const dueDate = new Date(formState.DueDate);
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const dueDateEl = document.querySelector('#DueDate');
      const dueDateError = document.querySelector('#error_DueDate');
      if (isNaN(dueDate.getTime()) || dueDate < today) {
        dueDateEl?.classList.add('error');
        if (dueDateError) {
          dueDateError.innerHTML = isNaN(dueDate.getTime()) ? 'Invalid Due Date' : 'Due date needs to be in the future.';
          dueDateError.classList.remove('hidden');
        }
        hasFormErrors = true;
      } else {
        dueDateEl?.classList.remove('error');
        dueDateError?.classList.add('hidden');
      }
    }

    setHasErrors(hasFormErrors);
    if (hasFormErrors) return;

    // Navigate to loading screen first
    navigate('/loading-estimate');
    
    // Small delay to ensure loading screen renders, then make API call
    setTimeout(async () => {
      try {
        // Find the selected insurance provider to get full details
        console.log('DEBUG: formState.insuranceProvider:', formState.insuranceProvider);
        console.log('DEBUG: state.insuranceProviders length:', state.insuranceProviders.length);
        console.log('DEBUG: first 3 providers:', state.insuranceProviders.slice(0, 3));
      
      // Handle data type mismatch: form stores string, API may return number
      const selectedProvider = state.insuranceProviders.find(
        p => p.id == formState.insuranceProvider || p.id === Number(formState.insuranceProvider)
      );
      
      console.log('DEBUG selectedProvider lookup:', {
        formStateProvider: formState.insuranceProvider,
        formStateType: typeof formState.insuranceProvider,
        foundProvider: selectedProvider,
        phoenixPayerId: selectedProvider?.phoenix_payer_id
      });

      // Build QuestionAnswer array (array with nulls and question objects at specific indices)
      const questionAnswer = [];
      state.currentTestQuestions.forEach((question) => {
        const questionId = parseInt(question.id);
        const answerKey = question.id === 3 ? `HighRisk_${question.id}` : (question.id === 9 ? `FamilyHistory_${question.id}` : `MultiFetalGestation_${question.id}`);
        const answerValue = formState[answerKey];
        
        if (answerValue) {
          questionAnswer[questionId] = {
            id: questionId,
            answer: { id: answerValue }
          };
        }
      });

      // Build tests array with questions
      const tests = state.phoenixTestId ? [{
        id: String(state.phoenixTestId),
        questions: state.currentTestQuestions
          .filter(q => {
            const answerKey = q.id === 3 ? `HighRisk_${q.id}` : (q.id === 9 ? `FamilyHistory_${q.id}` : `MultiFetalGestation_${q.id}`);
            return formState[answerKey];
          })
          .map(q => ({
            id: parseInt(q.id),
            answer: {
              id: formState[q.id === 3 ? `HighRisk_${q.id}` : (q.id === 9 ? `FamilyHistory_${q.id}` : `MultiFetalGestation_${q.id}`)]
            }
          }))
      }] : null;

      const highRiskText = getQuestionOptionText(3, formState.HighRisk_3);
      const multiFetalGestationText = getQuestionOptionText(8, formState.MultiFetalGestation_8);
      const familyHistoryText = getQuestionOptionText(9, formState.FamilyHistory_9);

      console.log('[DEBUG MultiFetal] MultiFetalGestation_8 optionId:', formState.MultiFetalGestation_8);
      console.log('[DEBUG MultiFetal] MultiFetalGestation text resolved:', JSON.stringify(multiFetalGestationText));
      console.log('[DEBUG MultiFetal] Questions available for id=8:', JSON.stringify(state.currentTestQuestions?.find(q => Number(q.id) === 8)));
      console.log('[DEBUG FamilyHistory] FamilyHistory_9 optionId:', formState.FamilyHistory_9);
      console.log('[DEBUG FamilyHistory] FamilyHistory text resolved:', JSON.stringify(familyHistoryText));
      console.log('[DEBUG FamilyHistory] Questions available for id=9:', JSON.stringify(state.currentTestQuestions?.find(q => Number(q.id) === 9)));

      // Get TradingPartnerId with full structure (match staging format)
      const tradingPartnerId = selectedProvider ? {
        key: String(selectedProvider.id), // String like staging
        value: selectedProvider.tradingParterId || '',
        tp_id: String(selectedProvider.tp_id || '') // String like staging
      } : { key: '', value: '', tp_id: '' };

      // Build the user payload matching the working example structure
      const user = {
        ...state.personalInfoObject,
        ...state.insuranceInfoObject,
        
        // Test information
        TestCategory: state.actualTestCategoryName || '',
        ActualTestCategory: state.actualTestCategoryName || '',
        TestName: state.selectedTestNameForSF || '',
        TestBundle: state.currentTestCode ? { key: state.currentTestCode } : {},
        
        // Insurance provider details
        insuranceProvider: formState.insuranceProvider || '',
        TradingPartnerId: tradingPartnerId,
        PayerId: selectedProvider?.phoenix_payer_id || '',
        PayerName: selectedProvider?.phoenix_payer_id || '',
        DisplayName: selectedProvider?.displayName || '',
        
        // Service Type for phoenix
        ServiceType: { key: "5" },
        
        // Provider information
        Provider_Name: selectedProvider?.provider_name || "Sequenom, Inc.",
        
        // Coverage status flags
        CoverageActive: true,
        
        // Coverage information from trading partner (keep as date strings for Phoenix)
        coverage_twins: selectedProvider?.coverage_twins === "1970-01-01 00:00:00" ? '' : (selectedProvider?.coverage_twins || ''),
        coverage_triplets: selectedProvider?.coverage_triplets === "1970-01-01 00:00:00" ? '' : (selectedProvider?.coverage_triplets || ''),
        coverage_four_or_more: selectedProvider?.coverage_four_or_more === "1970-01-01 00:00:00" ? '' : (selectedProvider?.coverage_four_or_more || ''),
        average_risk_coverage: selectedProvider?.average_risk_coverage || '',
        
        // Question answers
        QuestionAnswer: questionAnswer,
        tests: tests,
        
        // Match the legacy app: backend rules expect the selected option text.
        HighRisk: highRiskText,
        MultiFetalGestation: multiFetalGestationText,
        FamilyHistory: familyHistoryText,
        
        // Validation and requirement flags
        isMember_StateRequired: true,
        isMember_GenderRequired: false,
        isMember_PrimaryPhoneValid: true,
        isMember_Id_Enabled: formState.isMember_Id_Enabled,
        isMember_Id_Required: formState.isMember_Id_Enabled,
        isMember_DueDateValid: true,
        Member_Id_Is_Enabled: formState.isMember_Id_Enabled,
        
        // Phoenix test info
        phoenixTestId: String(state.phoenixTestId || ''), // String like staging
        
        // Captcha response (from reCAPTCHA component)
        RecaptchaResponse: recaptchaResponse,
        
        // Session info
        token: state.token,
        id: state.id,
        action: 'newEstimateRequest'
      };

      console.log('DEBUG: Complete payload being sent to backend:', JSON.stringify(user, null, 2));

      const response = await api.getCostEstimate({ user }, state.token);
      
      // Update estimate info with response data
      if (response.CustomerDataFinal) {
        const customerData = response.CustomerDataFinal;
        updateEstimateInfo('GroupNumber', customerData.GroupNumber);
        updateEstimateInfo('CostEstimateCurrency', customerData.CostEstimateCurrency);
        updateEstimateInfo('CoverageActive', customerData.CoverageActive);
      }
      
      if (response.FrontEndMessage) {
        const frontEndMessage = response.FrontEndMessage;
        updateEstimateInfo('response_for_front_end', frontEndMessage.response.response_for_front_end);
        updateEstimateInfo('response_for_front_end_two', frontEndMessage.response.response_for_front_end_two);
        updateEstimateInfo('include_legal_disclaimer', frontEndMessage.response.include_legal_disclaimer);
        updateEstimateInfo('legal_disclaimer', frontEndMessage.response.legal_disclaimer);
        updateEstimateInfo('likely_to_proceed', frontEndMessage.response.likely_to_proceed);

        // Replace [TEST] placeholder with actual test name
        let additionalActionsMessage = frontEndMessage.response.additional_actions_message || '';
        if (additionalActionsMessage) {
          additionalActionsMessage = additionalActionsMessage.replace('[TEST]', state.selectedTestNameForSF || state.CurrentTestName || '');
        }
        updateEstimateInfo('additional_actions_message', additionalActionsMessage);

        // Replace [COST_ESTIMATE_ID] placeholder with actual CostEstimateNumber like old system
        let costEstimateIdMessage = frontEndMessage.response.cost_estimate_id_message;
        if (response.CostEstimateNumber && costEstimateIdMessage) {
          costEstimateIdMessage = costEstimateIdMessage.replace('[COST_ESTIMATE_ID]', response.CostEstimateNumber);
        }
        updateEstimateInfo('cost_estimate_id_message', costEstimateIdMessage);

        // Treat 0 as a valid estimate amount.
        const hasReturnedCostEstimate = response.CustomerDataFinal?.CostEstimate !== undefined
          && response.CustomerDataFinal?.CostEstimate !== null
          && response.CustomerDataFinal?.CostEstimate !== '';

        if (frontEndMessage.response.contains_cost_estimate && hasReturnedCostEstimate) {
          updateEstimateInfo('CostEstimate', response.CustomerDataFinal.CostEstimate);
        }
      }

      updateEstimateInfo('receivedEstimate', false);
      updateEstimateInfo('estimateID', response.estimateID || '');
      updateEstimateInfo('CostEstimateNumber', response.CostEstimateNumber || '');
      updateEstimateInfo('salesforceId', response.CostEstimateRecordResult?.Id || '');
      updateEstimateInfo('MoveForwardAnswer', '');

      updateState({
        currentStep: state.currentStep + 1,
        currentStepButtonLabel: 'Start Over'
      });
      
      navigate('/estimate');
      } catch (error) {
        console.error('Failed to get cost estimate:', error);
        updateEstimateInfo('receivedEstimate', false);
        updateState({
          currentStep: state.currentStep + 1,
          currentStepButtonLabel: 'Start Over'
        });
        // For now, still navigate to show the error state
        navigate('/estimate');
      }
    }, 300); // Small delay to show loading screen
  };

  const divClear = { clear: 'both' };
  const red = { color: 'red' };

  const stateOptions = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming', 'District of Columbia', 'Puerto Rico', 'Guam', 'American Samoa', 'U.S. Virgin Islands', 'Northern Mariana Islands'];

  return [
    <article key={1} className="cost-container__content">
      <header>
        <h4>Enter your Insurance information</h4>
        <a className="cost-back-btn" onClick={goBack}>
          <i className="fa fa-arrow-left" aria-hidden="true"></i>Back
        </a>
        <em className="form-note">Note: All fields with * are required</em>
      </header>
      <section>
        <p id="errorMessage" style={red} className={!hasErrors ? 'errors hidden' : 'errors'}>
          We're sorry, but there are additional fields that need to be completed before you proceed. Please see the highlighted fields.
        </p>
        <div className="cost-service-form">
          <form className="cost-form" id="insuranceInformation" action="">
            <div className="form-container">
              <SingleInput
                isEnabled="true"
                inputType="text"
                className="cost-form-input"
                title="Insurance Keyword Filter"
                id="insuranceTextFilter"
                name="insuranceTextFilter"
                controlFunc={handleTextFilterChange}
                content={formState.insuranceTextFilter}
                isRequired={false}
                hasToolTipInfo=""
                placeholder="Type a word here to filter insurance list below"
              />

              {/* <DynamicSelect
                title="Filter by State"
                name="insuranceState"
                id="insuranceState"
                className="cost-select"
                placeholder="Select"
                controlFunc={handleStateChange}
                options={stateOptions}
                selectedOption={formState.insuranceStateSelection}
              /> */}

              <div style={divClear}></div>

              <DynamicSelect
                title="Insurance*"
                name="insuranceProvider"
                id="insuranceProvider"
                className="cost-select"
                placeholder="Make a selection"
                controlFunc={handleInsuranceProviderChange}
                options={filteredProviders}
                isRequired={true}
                hasToolTipInfo=""
                selectedOption={formState.insuranceProvider}
              />
              
              <p>If you don't see your carrier listed, call us at 844.799.3243</p>

              {filteredProviders.length === 0 && (
                <p style={red}>No insurance providers match your search. Try adjusting your filters.</p>
              )}

              <SingleInput
                isEnabled="true"
                inputType="text"
                className="cost-form-input"
                title={formState.isMember_Id_Enabled ? "Subscriber ID* (include all letters and numbers)" : "Subscriber ID"}
                id="Member_Id"
                name="Member_Id"
                controlFunc={(e) => handleInputChange('Member_Id', e.target.value)}
                content={formState.Member_Id}
                isRequired={formState.isMember_Id_Enabled}
                hasToolTipInfo=""
                placeholder={formState.isMember_Id_Enabled ? "Enter your Subscriber Id" : "Subscriber Id not required."}
              />

              <div style={divClear}></div>

              {/* Dynamically render questions based on currentTestQuestions from API */}
              {state.currentTestQuestions && state.currentTestQuestions.length > 0 && (
                <div className="form-item__full">
                  <h4>Additional Questions</h4>
                  <hr />
                  <p>Insurance companies often need the following information to determine your coverage.</p>
                </div>
              )}

              {state.currentTestQuestions && state.currentTestQuestions.map((question, index) => {
                if (question.type === 'SELECTION') {
                  if (question.id === 3) {
                    // HighRisk - pregnancy at increased risk
                    return (
                      <DynamicSelect
                        key={index}
                        title={question.displayName + '*'}
                        name={'HighRisk_' + question.id}
                        id={'HighRisk_' + question.id}
                        className="cost-select"
                        placeholder="Make a selection"
                        controlFunc={(e) => handleInputChange('HighRisk_' + question.id, e.target.value)}
                        options={question.options || []}
                        selectedOption={formState['HighRisk_' + question.id] || ''}
                      />
                    );
                  } else if (question.id === 8) {
                    // MultiFetalGestation - multiple babies
                    return (
                      <DynamicSelect
                        key={index}
                        title={question.displayName + '*'}
                        name={'MultiFetalGestation_' + question.id}
                        id={'MultiFetalGestation_' + question.id}
                        className="cost-select"
                        placeholder="Make a selection"
                        controlFunc={(e) => handleInputChange('MultiFetalGestation_' + question.id, e.target.value)}
                        options={question.options || []}
                        selectedOption={formState['MultiFetalGestation_' + question.id] || ''}
                      />
                    );
                  } else if (question.id === 9) {
                    // FamilyHistory
                    return (
                      <DynamicSelect
                        key={index}
                        title={question.displayName + '*'}
                        name={'FamilyHistory_' + question.id}
                        id={'FamilyHistory_' + question.id}
                        className="cost-select"
                        placeholder="Make a selection"
                        controlFunc={(e) => handleInputChange('FamilyHistory_' + question.id, e.target.value)}
                        options={question.options || []}
                        selectedOption={formState['FamilyHistory_' + question.id] || ''}
                      />
                    );
                  }
                } else if (question.type === 'DATE_PICKER') {
                  if (question.id === 7) {
                    // Patient Due Date
                    return (
                      <div key={index}>
                        <div className="form-item">
                          <label htmlFor="DueDate">{question.displayName}</label>
                          <input
                            type="date"
                            id="DueDate"
                            name="DueDate"
                            className="cost-form-input"
                            value={formState.DueDate || ''}
                            onChange={(e) => handleInputChange('DueDate', e.target.value)}
                            placeholder="MM/DD/YYYY"
                          />
                          <span id="error_DueDate" className="error-message hidden"></span>
                        </div>
                        <div style={divClear}></div>
                      </div>
                    );
                  }
                }
                return null;
              })}

            </div>
            
            {/* Cloudflare Turnstile Component (matches old cost estimator) */}
            <div className="form-item__full" style={{ marginTop: '20px' }}>
              <Turnstile
                sitekey='0x4AAAAAAAynbtkAr_tTKBIj'
                onSuccess={handleTurnstileCallback}
              />
              <span id="turnstileError" className="error-message hidden">Please verify you are a human!</span>
            </div>
            
          </form>
        </div>
      </section>
    </article>,
    <h4 key={2}>Don't worry if you don't immediately receive an estimate. Frequently there is more than one financial option available to you - Typically related to insurance coverage or timing of deductible, and it's easier to discuss the options on a phone call. If you receive a "we will contact you message," a member of our team will reach out to you in 30 minutes or at a time convenient to you.</h4>,
    <Button 
      key={3} 
      isEnabled={true} 
      label="Continue" 
      controlFunc={handleContinue} 
    />
  ];
}

export default InsuranceInfo;
