import React, { useEffect } from 'react';
import { useAppContext } from '../context/AppContext';
import { config } from '../utils/api';

const LoadingEstimate = () => {
  const { state } = useAppContext();
  const imageSourceEndpoint = config.IMAGE_SOURCE;
  
  useEffect(() => {
    // Set page title
    document.title = 'Cost Estimator - Calculating...';
    
    return () => {
      document.title = 'Cost Estimator';
    };
  }, []);
  
  return (
    <article className="cost-container__content">
      <header className="loading">
        <h4>Please wait while we calculate your estimated cost.</h4>
      </header>
      <section>
        <div className="cost-waiting">
          <h3 className="cost-blue-alert"><b>Note:</b> It may take up to 30 seconds to find out your estimated cost.</h3>
          <div className="cost-loading-container">
            <img src={imageSourceEndpoint + '/assets/images/Loading_icon.gif'} alt="" />
          </div>
        </div>
      </section>
    </article>
  );
};

export default LoadingEstimate;