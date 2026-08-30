import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import Button from '../components/Button';
import api, { config } from '../utils/api';

const followUpOptions = [
  { value: 'I want to ask my physician more questions about the test', label: 'I want to ask my physician more questions about the test' },
  { value: 'Not the right time', label: 'Not the right time' },
  { value: 'Not sure of the value of the test', label: 'Not sure of the value of the test' },
  { value: 'I may look at other testing options/companies', label: 'I may look at other testing options/companies' },
  { value: 'Too expensive', label: 'Too expensive' }
];

function FollowUp() {
  const { state, updateEstimateInfo, updateState } = useAppContext();
  const navigate = useNavigate();
  const [moveForwardAnswer, setMoveForwardAnswer] = useState(Array.isArray(state.estimateInfoObject?.MoveForwardAnswer) ? state.estimateInfoObject.MoveForwardAnswer : []);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState('');

  const imageSourceEndpoint = config.IMAGE_SOURCE;

  // Route guard: redirect if no estimate response data
  useEffect(() => {
    if (!state.estimateInfoObject || !state.estimateInfoObject.response_for_front_end) {
      console.log('No estimate response, redirecting to home');
      navigate('/');
    }
  }, [state.estimateInfoObject, navigate]);

  const handleFollowUpChange = (value) => {
    setMoveForwardAnswer(prev => {
      const newAnswers = prev.includes(value)
        ? prev.filter(item => item !== value)
        : [...prev, value];
      updateEstimateInfo('MoveForwardAnswer', newAnswers);
      return newAnswers;
    });
  };

  const handleFollowUpSubmission = async () => {
    if (isSubmitting) {
      return;
    }

    setSubmitError('');
    setIsSubmitting(true);

    const user = {
      ...state.personalInfoObject,
      ...state.insuranceInfoObject,
      ...state.estimateInfoObject,
      MoveForwardAnswer: moveForwardAnswer,
      token: state.token,
      id: state.id
    };

    try {
      const response = await api.getCostEstimate({
        user: {
          ...user,
          action: 'updateSalesforceObject'
        }
      }, state.token);
      const isSaved = response?.CostEstimateUpdateResult?.message === 'Successfully updated a Cost Estimate Record.';

      if (isSaved) {
        updateState({
          currentStep: 7,
          currentStepButtonLabel: 'Start Over'
        });
        navigate('/finalconfirmation');
        return;
      }

      setSubmitError('There was a problem saving your response. Please contact us at 844-799-3243.');
    } catch (error) {
      setSubmitError('There was a problem saving your response. Please contact us at 844-799-3243.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const startOverClick = () => {
    updateEstimateInfo('receivedEstimate', false);
    window.location.hash = '#/';
    window.setTimeout(() => {
      window.location.reload();
    }, 50);
  };

  return [
    <article key="1" className="cost-container__content">
      <header>
        { 
          (state.estimateInfoObject['additional_actions_message'] !== '')
            ? <h4>{state.estimateInfoObject['additional_actions_message']}</h4> 
            : <h4>Your estimated cost.</h4>
        }
      </header>
      <section>
        {submitError && <p id="errorMessage" className="errors">{submitError}</p>}
        <div className="cost-estimate">
          <h3>What is the reason why you may not be proceeding?</h3>
          <div className="contact-box">
            <form className="cost-form" action="">
              <div className="form-container">
                <div className="form-item_full">
                  <label htmlFor=""></label>
                  {followUpOptions.map((option, index) => (
                    <label key={index}>
                      <input
                        type="checkbox"
                        value={option.value}
                        checked={moveForwardAnswer.includes(option.value)}
                        onChange={() => handleFollowUpChange(option.value)}
                      />
                      {option.label}
                    </label>
                  ))}
                  <div className="cost-button-container-left">
                    <br />
                    <button type="button" className="cost-btn" onClick={handleFollowUpSubmission} disabled={isSubmitting}>Submit</button>
                    <img id="imgSavingFollowUp" className={isSubmitting ? '' : 'hidden'} src={imageSourceEndpoint + '/assets/images/Loading_icon.gif'} alt="" />
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
    </article>,
    <Button key="2" isEnabled="true" label={state.currentStepButtonLabel} controlFunc={startOverClick} />
  ];
}

export default FollowUp;