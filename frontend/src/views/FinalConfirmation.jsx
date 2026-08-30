import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import Button from '../components/Button';

function FinalConfirmation() {
  const { state, updateEstimateInfo } = useAppContext();
  const navigate = useNavigate();

  // Route guard: redirect if no estimate response data
  useEffect(() => {
    if (!state.estimateInfoObject || !state.estimateInfoObject.response_for_front_end) {
      console.log('No estimate response, redirecting to home');
      navigate('/');
    }
  }, [state.estimateInfoObject, navigate]);

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
        <h4>Thank you!</h4>
      </header>
      <section>
        <div className="cost-thankyou">
          <h3>Please let us know if we can help you with more of your genetic testing questions or needs.</h3>
          <span className="service-note">Our business hours are Monday through Friday, 8 AM to 7 PM EST.</span>
        </div>
      </section>
    </article>,
    <Button key="2" isEnabled="true" label={state.currentStepButtonLabel} controlFunc={startOverClick} />
  ];
}

export default FinalConfirmation;