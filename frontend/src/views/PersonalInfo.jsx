import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import SingleInput from '../components/SingleInput';
import SingleInputMask from '../components/SingleInputMask';
import Button from '../components/Button';
import Select from '../components/Select';
import ConsentSelect from '../components/ConsentSelect';
import dayjs from 'dayjs';
import api from '../utils/api';

const consentToContactOptions = ['Yes', 'No'];
const consentToLeaveMessageOptions = ['Yes', 'No'];
const genderOptions = ['Male', 'Female'];
const stateOptions = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California', 'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii', 'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana', 'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota', 'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire', 'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota', 'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island', 'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont', 'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming', 'District of Columbia', 'Puerto Rico', 'Guam', 'American Samoa', 'U.S. Virgin Islands', 'Northern Mariana Islands'];
const identifierOptions = ['Patient', 'Health Care Provider', 'Spouse/Domestic Partner'];
const baseCallbackOptions = [
  { id: 'AM | 8am – 10am EST', displayName: '8am – 10am EST' },
  { id: 'AM | 10am – 12pm EST', displayName: '10am - 12pm EST' },
  { id: 'AM | 12pm – 2pm EST', displayName: '12pm - 2pm EST' },
  { id: 'PM | 2pm – 4pm EST', displayName: '2pm – 4pm EST' },
  { id: 'PM | 4pm - 7pm EST', displayName: '4pm - 7pm EST' }
];

function buildCallbackOptions(isASAPAvailable) {
  const etParts = new Intl.DateTimeFormat('en-US', {
    timeZone: 'America/New_York',
    hour: 'numeric',
    minute: 'numeric',
    hour12: false,
    weekday: 'short'
  }).formatToParts(new Date());
  const weekday = etParts.find(p => p.type === 'weekday')?.value;
  const hour = parseInt(etParts.find(p => p.type === 'hour')?.value || '0', 10);
  const minute = parseInt(etParts.find(p => p.type === 'minute')?.value || '0', 10);
  const totalMinutes = hour * 60 + minute;
  const isWeekday = weekday !== 'Sat' && weekday !== 'Sun';
  const isWithinHours = totalMinutes >= 480 && totalMinutes <= 1110; // 8:00am–6:30pm
  const options = [...baseCallbackOptions];
  if (isASAPAvailable && isWeekday && isWithinHours) {
    options.push({
      id: 'ASAP | Call me now (I am at the physician\'s office or lab)',
      displayName: 'Call me now (I am at the physician\'s office or lab)'
    });
  }
  return options;
}

function PersonalInfo() {
  const { state, updatePersonalInfo, updateState } = useAppContext();
  const navigate = useNavigate();

  // Route guard: redirect if no test has been selected
  useEffect(() => {
    if (!state.phoenixTestId) {
      navigate('/');
    }
  }, [state.phoenixTestId, navigate]);

  // Determine if gender/state is required based on test category
  const isNIPT = state.actualTestCategoryName === 'NIPT';
  const isMember_GenderRequired = !isNIPT;
  const isMember_StateRequired = isNIPT;
  const defaultGender = isNIPT ? 'Female' : 'Unknown';

  const [formState, setFormState] = useState({
    Member_FirstName: state.personalInfoObject.Member_FirstName || '',
    Member_LastName: state.personalInfoObject.Member_LastName || '',
    Member_BirthDate: state.personalInfoObject.Member_BirthDate || '',
    Member_Gender: state.personalInfoObject.Member_Gender || defaultGender,
    Member_State: state.personalInfoObject.Member_State || '',
    Member_PrimaryPhone: state.personalInfoObject.Member_PrimaryPhone || '',
    Member_PrimaryPhoneType: state.personalInfoObject.Member_PrimaryPhoneType || '',
    CallbackSelection: state.personalInfoObject.CallbackSelection || '',
    ConsentToLeaveMessage: state.personalInfoObject.ConsentToLeaveMessage || '',
    ConsentToContact: state.personalInfoObject.ConsentToContact || '',
    Member_Email: state.personalInfoObject.Member_Email || '',
    Member_Identifier: state.personalInfoObject.Member_Identifier || ''
  });

  const callbackSelectionOptions = buildCallbackOptions(state.estimateInfoObject?.isASAPAvailable);

  const [isCallbackEnabled, setIsCallbackEnabled] = useState(
    state.personalInfoObject.ConsentToContact !== 'No'
  );

  const [hasErrors, setHasErrors] = useState(false);
  const [isValidating, setIsValidating] = useState(false);

  // Validation functions
  const validateEmail = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
  };

  const validateBirthDate = (birthdate) => {
    const now = dayjs();
    const hundredYearsAgo = dayjs().subtract(100, 'year');
    const bday = dayjs(birthdate, 'MM/DD/YYYY', true);
    
    if (!bday.isValid()) {
      return {
        status: 'InvalidBirthdate',
        message: 'The current date entered is in an invalid format, please update.'
      };
    }
    
    if (bday.isBefore(hundredYearsAgo) || bday.isAfter(now)) {
      return {
        status: 'InvalidBirthdate',
        message: 'The current date entered is in an invalid format, please update.'
      };
    }
    
    return {
      status: 'ValidBirthdate',
      message: ''
    };
  };

  const validatePhoneNumber = async (phoneNumber) => {
    const cleanPhone = phoneNumber.replace(/\D/g, '');
    
    if (cleanPhone.length !== 10) {
      return {
        phoneNumber: phoneNumber,
        type: 'Undetermined',
        ext: null,
        status: 'ErrorRetrieving'
      };
    }

    try {
      const data = await api.verifyPhone(cleanPhone, state.token);
      return data.phone_lookup.results;
    } catch (error) {
      console.error('Phone validation error:', error);
      return {
        phoneNumber: phoneNumber,
        type: 'Undetermined',
        ext: null,
        status: 'SystemDown'
      };
    }
  };

  useEffect(() => {
    // Set gender requirements based on test category
    if (isNIPT) {
      updatePersonalInfo('Member_Gender', 'Female');
      updatePersonalInfo('isMember_StateRequired', true);
      updatePersonalInfo('isMember_GenderRequired', false);
    } else {
      updatePersonalInfo('isMember_StateRequired', false);
      updatePersonalInfo('isMember_GenderRequired', true);
    }
  }, []);

  const handleInputChange = (field, value) => {
    setFormState(prev => ({ ...prev, [field]: value }));
    updatePersonalInfo(field, value);
  };

  const handleConsentToContactChange = (field, value) => {
    setFormState(prev => ({ ...prev, [field]: value }));
    updatePersonalInfo(field, value);
    
    // Enable/disable callback selection based on consent
    if (value === 'No') {
      setIsCallbackEnabled(false);
      setFormState(prev => ({ ...prev, CallbackSelection: '', ConsentToLeaveMessage: '' }));
      updatePersonalInfo('CallbackSelection', '');
      updatePersonalInfo('ConsentToLeaveMessage', '');
    } else {
      setIsCallbackEnabled(true);
    }
  };

  const goBack = () => {
    navigate(-1);
  };

  const handleContinue = async () => {
    setIsValidating(true);
    setHasErrors(false);
    let validationErrors = false;

    // Clear all error classes first
    const errorElements = document.querySelectorAll('.error');
    errorElements.forEach(el => el.classList.remove('error'));
    const errorMessages = document.querySelectorAll('[id^="error_"]');
    errorMessages.forEach(el => {
      el.classList.add('hidden');
      el.innerHTML = '';
    });

    // Validate First Name
    if (formState.Member_FirstName === '') {
      document.querySelector('#Member_FirstName')?.classList.add('error');
      validationErrors = true;
    }

    // Validate Last Name
    if (formState.Member_LastName === '') {
      document.querySelector('#Member_LastName')?.classList.add('error');
      validationErrors = true;
    }

    // Validate Birth Date
    if (formState.Member_BirthDate === '') {
      document.querySelector('#Member_BirthDate')?.classList.add('error');
      validationErrors = true;
    } else {
      const bdayValidation = validateBirthDate(formState.Member_BirthDate);
      if (bdayValidation.status !== 'ValidBirthdate') {
        const errorEl = document.querySelector('#error_Member_BirthDate');
        if (errorEl) {
          errorEl.innerHTML = bdayValidation.message;
          errorEl.classList.remove('hidden');
        }
        document.querySelector('#Member_BirthDate')?.classList.add('error');
        validationErrors = true;
      }
    }

    // Validate State (if required for NIPT)
    if (isMember_StateRequired && (formState.Member_State === 'Select' || formState.Member_State === '')) {
      document.querySelector('#Member_State')?.parentNode?.classList.add('error');
      validationErrors = true;
    }

    // Validate Identifier
    if (formState.Member_Identifier === 'Select' || formState.Member_Identifier === '') {
      document.querySelector('#Member_Identifier')?.parentNode?.classList.add('error');
      validationErrors = true;
    }

    // Validate Email
    if (formState.Member_Email === '') {
      document.querySelector('#Member_Email')?.classList.add('error');
      validationErrors = true;
    } else if (!validateEmail(formState.Member_Email)) {
      const errorEl = document.querySelector('#error_Member_Email');
      if (errorEl) {
        errorEl.innerHTML = 'Email address is not valid.';
        errorEl.classList.remove('hidden');
      }
      document.querySelector('#Member_Email')?.classList.add('error');
      validationErrors = true;
    }

    // Validate Consent to Contact
    if (formState.ConsentToContact === 'Select' || formState.ConsentToContact === '') {
      document.querySelector('#ConsentToContact')?.parentNode?.classList.add('error');
      validationErrors = true;
    }

    // Validate Consent to Leave Message (if consent is Yes)
    if (isCallbackEnabled && (formState.ConsentToLeaveMessage === 'Select' || formState.ConsentToLeaveMessage === '')) {
      document.querySelector('#ConsentToLeaveMessage')?.parentNode?.classList.add('error');
      validationErrors = true;
    }

    // Validate Callback Selection (if consent is Yes)
    if (isCallbackEnabled && (formState.CallbackSelection === 'Select' || formState.CallbackSelection === '')) {
      document.querySelector('#CallbackSelection')?.parentNode?.classList.add('error');
      validationErrors = true;
    }

    // Validate Phone Number (with API call)
    const cleanPhone = formState.Member_PrimaryPhone.replace(/\D/g, '');
    if (cleanPhone === '') {
      document.querySelector('#Member_PrimaryPhone')?.classList.add('error');
      validationErrors = true;
      setIsValidating(false);
      setHasErrors(true);
      window.scrollTo(0, 0);
      return;
    }

    const phoneValidation = await validatePhoneNumber(cleanPhone);
    
    if (phoneValidation.status === 'ValidNumber' || phoneValidation.status === 'SystemDown') {
      // Valid phone or system down (allow to proceed)
      updatePersonalInfo('isMember_PrimaryPhoneValid', true);
      updatePersonalInfo('Member_PrimaryPhoneType', phoneValidation.type);
      document.querySelector('#error_Member_PrimaryPhone')?.classList.add('hidden');
      document.querySelector('#Member_PrimaryPhone')?.classList.remove('error');
    } else {
      // Invalid phone
      const errorEl = document.querySelector('#error_Member_PrimaryPhone');
      if (errorEl) {
        if (phoneValidation.status === 'ErrorRetrieving') {
          errorEl.innerHTML = 'Trying to validate phone number, please wait...';
        } else {
          errorEl.innerHTML = 'Contact phone is not valid, please enter a valid phone number.';
        }
        errorEl.classList.remove('hidden');
      }
      document.querySelector('#Member_PrimaryPhone')?.classList.add('error');
      updatePersonalInfo('isMember_PrimaryPhoneValid', false);
      updatePersonalInfo('Member_PrimaryPhoneType', phoneValidation.type);
      validationErrors = true;
    }

    setIsValidating(false);

    if (validationErrors) {
      setHasErrors(true);
      window.scrollTo(0, 0);
      return;
    }

    // All validation passed - update all fields and navigate
    Object.keys(formState).forEach(key => {
      updatePersonalInfo(key, formState[key]);
    });
    
    updateState({
      currentStep: state.currentStep + 1
    });
    
    navigate('/insuranceInformation');
  };

  const divClear = { clear: 'both' };
  const red = { color: 'red' };

  return [
    <article key={1} className="cost-container__content">
      <header>
        <h4>Enter Patient Personal Information</h4>
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
          <form className="cost-form" id="personalInformation" action="">
            <div className="form-container">
              <SingleInput
                isEnabled="true"
                inputType="text"
                className="cost-form-input"
                title="Patient First Name*"
                id="Member_FirstName"
                name="Member_FirstName"
                controlFunc={(e) => handleInputChange('Member_FirstName', e.target.value)}
                content={formState.Member_FirstName}
                isRequired={true}
                hasToolTipInfo=""
                placeholder="Type first name"
              />
              
              <SingleInput
                isEnabled="true"
                inputType="text"
                className="cost-form-input"
                title="Patient Last Name*"
                id="Member_LastName"
                name="Member_LastName"
                controlFunc={(e) => handleInputChange('Member_LastName', e.target.value)}
                content={formState.Member_LastName}
                isRequired={true}
                hasToolTipInfo=""
                placeholder="Type last name"
              />
              
              <SingleInputMask
                mask="99/99/9999"
                maxLength="8"
                isEnabled="true"
                className="cost-form-input"
                title="Date of birth*"
                id="Member_BirthDate"
                name="Member_BirthDate"
                controlFunc={(e) => handleInputChange('Member_BirthDate', e.target.value)}
                content={formState.Member_BirthDate}
                isRequired={true}
                hasToolTipInfo=""
                placeholder=""
              />
              <p id="error_Member_BirthDate" className="error-message hidden"></p>
              
              {isMember_StateRequired && (
                <Select
                  title="State*"
                  name="Member_State"
                  id="Member_State"
                  className="cost-select"
                  placeholder="Select"
                  isRequired={true}
                  controlFunc={(e) => handleInputChange('Member_State', e.target.value)}
                  options={stateOptions}
                  hasToolTipInfo=""
                  selectedOption={formState.Member_State}
                />
              )}
              
              {isMember_StateRequired && <div style={divClear}></div>}
              
              <Select
                title="I am a*"
                name="Member_Identifier"
                id="Member_Identifier"
                className="cost-select"
                placeholder="Select"
                isRequired={true}
                controlFunc={(e) => handleInputChange('Member_Identifier', e.target.value)}
                options={identifierOptions}
                hasToolTipInfo="Please describe who is entering this cost estimate."
                selectedOption={formState.Member_Identifier}
              />
              
              <div className="form-item__full">
                <h4>Contact Information</h4>
                <hr />
                <p></p>
              </div>
              
              <SingleInputMask
                mask="(999) 999-9999"
                maxLength="10"
                isEnabled="true"
                className="cost-form-input"
                title="Phone*"
                id="Member_PrimaryPhone"
                name="Member_PrimaryPhone"
                controlFunc={(e) => handleInputChange('Member_PrimaryPhone', e.target.value)}
                content={formState.Member_PrimaryPhone}
                isRequired={true}
                hasToolTipInfo="This is needed and important so we can provide you with the most accurate estimate and your best testing options."
                placeholder="(999) 999-9999"
              />
              <p id="error_Member_PrimaryPhone" className="error-message hidden"></p>
              
              <SingleInput
                isEnabled="true"
                inputType="text"
                className="cost-form-input"
                title="Contact Email*"
                id="Member_Email"
                name="Member_Email"
                controlFunc={(e) => handleInputChange('Member_Email', e.target.value)}
                content={formState.Member_Email}
                isRequired={true}
                hasToolTipInfo=""
                placeholder="Enter your email address"
              />
              <p id="error_Member_Email" className="error-message hidden"></p>
            </div>
            
            <div className="form-container"></div>
            
            <div className="form-container authorize">
              <ConsentSelect
                hasToolTipInfo=""
                title="I authorize Labcorp to contact me. I understand that Labcorp will not share my information with others."
                name="ConsentToContact"
                id="ConsentToContact"
                className="cost-select"
                placeholder="Select"
                controlFunc={(e) => handleConsentToContactChange('ConsentToContact', e.target.value)}
                options={consentToContactOptions}
                isRequired={true}
                selectedOption={formState.ConsentToContact}
              />
              
              <div style={{ display: 'block' }}>
                <ConsentSelect
                  hasToolTipInfo=""
                  title="I authorize Labcorp to leave me a message"
                  name="ConsentToLeaveMessage"
                  id="ConsentToLeaveMessage"
                  className="cost-select"
                  placeholder="Select"
                  controlFunc={(e) => handleInputChange('ConsentToLeaveMessage', e.target.value)}
                  options={consentToLeaveMessageOptions}
                  isRequired={true}
                  selectedOption={formState.ConsentToLeaveMessage}
                />
              </div>

              <div style={{ display: 'block' }}>
                <Select
                  title="Time of Day Preference*"
                  name="CallbackSelection"
                  id="CallbackSelection"
                  className="cost-select"
                  placeholder="Select"
                  controlFunc={(e) => handleInputChange('CallbackSelection', e.target.value)}
                  options={callbackSelectionOptions}
                  isRequired={true}
                  hasToolTipInfo=""
                  selectedOption={formState.CallbackSelection}
                />
              </div>
            </div>
          </form>
        </div>
      </section>
    </article>,
    <Button 
      key={2} 
      isEnabled={!isValidating} 
      label={isValidating ? "Validating..." : "Continue"} 
      controlFunc={handleContinue} 
    />
  ];
}

export default PersonalInfo;
