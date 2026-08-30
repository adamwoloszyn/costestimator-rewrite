import { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import Button from '../components/Button';
import api, { config } from '../utils/api';

function Estimate() {
  const { state, updateState, updateEstimateInfo } = useAppContext();
  const navigate = useNavigate();
  const [debugVisible, setDebugVisible] = useState(false);
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState('');

  const imageSourceEndpoint = config.IMAGE_SOURCE;

  // Route guard: redirect if no estimate response data
  useEffect(() => {
    console.log('Estimate route guard check:', {
      hasEstimateObject: !!state.estimateInfoObject,
      hasResponse: !!state.estimateInfoObject?.response_for_front_end,
      receivedEstimate: state.estimateInfoObject?.receivedEstimate
    });
    
    if (!state.estimateInfoObject || !state.estimateInfoObject.response_for_front_end) {
      console.log('No estimate response, redirecting to home');
      navigate('/');
    } else if (!state.estimateInfoObject.receivedEstimate) {
      // Only set receivedEstimate flag if it's not already set
      updateEstimateInfo('receivedEstimate', true);
    }
  }, [state.estimateInfoObject, navigate]); // Removed updateEstimateInfo from dependencies

  const toggleDebugInfo = () => {
    setDebugVisible(!debugVisible);
  };

  const submitMoveForwardAnswer = async (moveForwardAnswer) => {
    const user = {
      ...state.personalInfoObject,
      ...state.insuranceInfoObject,
      ...state.estimateInfoObject,
      MoveForwardAnswer: moveForwardAnswer,
      token: state.token,
      id: state.id
    };

    const response = await api.getCostEstimate({
      user: {
        ...user,
        action: 'updateSalesforceObject'
      }
    }, state.token);
    return response?.CostEstimateUpdateResult?.message === 'Successfully updated a Cost Estimate Record.';
  };

  const handleProceedWithTestClick = async (choice) => {
    if (choice === 'yes') {
      if (isSubmitting) {
        return;
      }

      setSubmitError('');
      setIsSubmitting(true);
      updateEstimateInfo('MoveForwardAnswer', 'Yes');

      try {
        const wasSaved = await submitMoveForwardAnswer('Yes');

        if (wasSaved) {
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
    } else {
      updateState({
        currentStep: 6,
        currentStepButtonLabel: 'Start Over'
      });
      navigate('/followup');
    }
  };

  const startOverClick = () => {
    updateEstimateInfo('receivedEstimate', false);
    window.location.hash = '#/';
    window.setTimeout(() => {
      window.location.reload();
    }, 50);
  };

  const centerImages = { textAlign: 'center' };
  const hideImage = { display: 'none' };

  const estimateInfo = state.estimateInfoObject;
  const hasCostEstimate = estimateInfo['CostEstimate'] !== undefined && estimateInfo['CostEstimate'] !== null && estimateInfo['CostEstimate'] !== '';

  if (hasCostEstimate) {
    return (
      <article className="cost-container__content">
        <header>
          { 
            (estimateInfo['additional_actions_message'] !== '')
              ? <h4>{estimateInfo['additional_actions_message']}</h4> 
              : <h4>Your estimated cost.</h4>
          }
        </header>
        <section>
          {/* Debug info commented out */}
          <div className="cost-estimate">
            <div className="noMargin" dangerouslySetInnerHTML={{ __html: estimateInfo['response_for_front_end'] }}></div>
            { estimateInfo['likely_to_proceed'] &&
              <span className="total-cost">
                ${estimateInfo['CostEstimate']}
              </span>
            }
            <h3 dangerouslySetInnerHTML={{ __html: estimateInfo['response_for_front_end_two'] }}></h3><br />
            <div dangerouslySetInnerHTML={{ __html: estimateInfo['cost_estimate_id_message'] }}></div><br />
            { 
              (estimateInfo['include_legal_disclaimer'])
                ? <div dangerouslySetInnerHTML={{ __html: estimateInfo['legal_disclaimer'] }}></div> 
                : <div></div> 
            }
            {
              estimateInfo['likely_to_proceed']
                ? <div className="proceed-with-test">
                    <h3>Based on this estimate, are you likely to proceed with testing?</h3>
                    {submitError && <p className="errors">{submitError}</p>}
                    <div className="split-btn-container">
                      <button className="cost-btn-dark faded" onClick={() => handleProceedWithTestClick('no')}>NO</button>
                      <button className="cost-btn-dark" onClick={() => handleProceedWithTestClick('yes')} disabled={isSubmitting}>YES</button>
                    </div>
                    <img style={isSubmitting ? {} : hideImage} id="imgSavingFollowUp" className={isSubmitting ? '' : 'hidden'} src={imageSourceEndpoint + '/assets/images/Loading_icon.gif'} alt="" />
                  </div>
                : <Button isEnabled="true" label="Start Over" controlFunc={startOverClick} />
            }
          </div>
        </section>
      </article>
    );
  } else {
    return [
      <article key="1" className="cost-container__content">
        <header>
          { 
            (estimateInfo['additional_actions_message'] !== '')
              ? <h4>{estimateInfo['additional_actions_message']}</h4> 
              : <h4>Your estimated cost.</h4>
          }
        </header>
        <section>   
          <div className="cost-estimate">
            <div className="cost-estimate" dangerouslySetInnerHTML={{ __html: estimateInfo['response_for_front_end'] }}></div>
            <div dangerouslySetInnerHTML={{ __html: estimateInfo['cost_estimate_id_message'] }}></div><br />
          </div>
        </section>
      </article>,
      <Button key="2" isEnabled="true" label={state.currentStepButtonLabel} controlFunc={startOverClick} />
    ];
  }
}

export default Estimate;