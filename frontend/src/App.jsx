import { useEffect, useState, useRef } from 'react';
import { HashRouter as Router, Routes, Route, useNavigate, useLocation } from 'react-router-dom';
import { useAppContext } from './context/AppContext';
import api from './utils/api';
import dayjs from 'dayjs';
import TestCategory from './views/TestCategory';
import TestList from './views/TestList';
import PersonalInfo from './views/PersonalInfo';
import InsuranceInfo from './views/InsuranceInfo';
import LoadingEstimate from './views/LoadingEstimate';
import Estimate from './views/Estimate';
import FollowUp from './views/FollowUp';
import FinalConfirmation from './views/FinalConfirmation';
import NotAvailable from './views/NotAvailable';
import Loading from './views/Loading';
import EnvIndicator from './components/EnvIndicator';
import StaticPayerObject from './data/StaticPayerObject';

function AppContent() {
  const { state, updateState, updateEstimateInfo } = useAppContext();
  const navigate = useNavigate();
  const location = useLocation();
  const [isInitialized, setIsInitialized] = useState(false);
  const initRef = useRef(false);

  // Get query parameter by name (from original App.js)
  const getParameterByName = (name, url) => {
    if (!url) url = window.location.href;
    name = name.replace(/[\[\]]/g, '\\$&');
    const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
    const results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
  };

  // API Call 1: Get Tests Available
  const getTestsAvailable = async (token) => {
    try {
      const data = await api.getTestsAvailable(token);
      updateState({
        staticTestCategories: data.test_categories
      });
    } catch (error) {
      console.error('getTestsAvailable failed:', error);
    }
  };

  // API Call 2: Check Site Status
  const checkSiteStatus = async (callback) => {
    try {
      const data = await api.getSiteStatus(state.token);
      const siteStatus = data['site_status'];
      const newToken = data['token'];
      const newId = data['id'];
      
      // Update token if provided
      if (newToken) {
        updateState({
          token: newToken,
          id: newId
        });
      }

      if (siteStatus?.status?.http_code === 200) {
        const now = dayjs();
        
        // Check ASAP availability
        const asapStatus = siteStatus.status.results["ASAP"];
        if (asapStatus?.IsAvailable) {
          updateEstimateInfo('isASAPAvailable', true);
        } else {
          updateEstimateInfo('isASAPAvailable', false);
        }

        // Check Estimator availability
        // IsAvailable: false means down immediately — OutageStart/End are informational only
        const estimatorStatus = siteStatus.status.results["Estimator"];
        const isEstimatorAvailable = estimatorStatus?.IsAvailable === true;
        updateEstimateInfo('isEstimatorAvailable', isEstimatorAvailable);
        
        if (callback) callback(isEstimatorAvailable, newToken);
        return true;
      } else {
        // Original keeps estimator available even on error
        updateEstimateInfo('isEstimatorAvailable', true);
        if (callback) callback(true, newToken);
        return true;
      }
    } catch (error) {
      console.error('checkSiteStatus failed:', error);
      updateEstimateInfo('isEstimatorAvailable', false);
      if (callback) callback(false, null);
      return false;
    }
  };

  // API Call 3: Get Banner Messages
  const getBannerMessage = async (token) => {
    try {
      const data = await api.getBannerMessages(token);
      if (data[0] && data[0].message) {
        updateState({
          bannerMessage: data[0].message
        });
      } else {
        updateState({
          bannerMessage: null
        });
      }
    } catch (error) {
      console.error('getBannerMessage failed:', error);
    }
  };

  // API Call 4: Get Phoenix Lookup Data
  const getPhoenixData = async (token, callback) => {
    try {
      const data = await api.getPhoenixLookup(token);
      
      if (data['phoenix_lookup']?.results?.error === undefined) {
        // Get trading partners after successful lookup
        getTradingPartners(token);
        
        updateState({
          insuranceStates: StaticPayerObject.insuranceStates,
          testCategories: data['phoenix_lookup']['results']['categories'],
          CaptchaSiteKey: data['phoenix_lookup']['results']['captchaSiteKey']
        });

        // Show test categories (DOM manipulation like original)
        setTimeout(() => {
          const testCategoriesLoading = document.querySelector('#testCategoriesLoading');
          const testCategories = document.querySelector('#testCategories');

          if (testCategoriesLoading && testCategories) {
            testCategoriesLoading.classList.add('hidden');
            testCategories.classList.remove('hidden');
          }
        }, 100);
        
        if (callback) callback(true);
        return true;
      } else {
        updateEstimateInfo('isEstimatorAvailable', false);
        if (callback) callback(false);
        return false;
      }
    } catch (error) {
      console.error('getPhoenixData failed:', error);
      updateEstimateInfo('isEstimatorAvailable', false);
      if (callback) callback(false);
      return false;
    }
  };

  // API Call 5: Get Trading Partners
  const getTradingPartners = async (token) => {
    try {
      const data = await api.getTradingPartners(token);
      console.log('Trading partners loaded successfully, count:', data.length);
      
      updateState({
        insuranceProviders: data
      });
    } catch (error) {
      console.error('getTradingPartners failed, using static data:', error);
      // Fallback to static data if API fails
      const staticData = StaticPayerObject.payerObjectResults;
      console.log('DEBUG: Using static data, count:', staticData.length);
      console.log('DEBUG: Static provider 449:', staticData.find(p => p.id === "449"));
      updateState({
        insuranceProviders: staticData
      });
    }
  };

  // Initialize app on mount
  useEffect(() => {
    const initializeApp = () => {
      if (initRef.current) return; // Prevent double initialization
      initRef.current = true;

      console.log('Initializing app...');

      // Check if user is billing team member
      const isBillingTeamMember = getParameterByName("callbackOption");
      
      if (isBillingTeamMember === "true") {
        updateState({ isBillingTeamMember: true });
      }

      // CRITICAL: Check site status FIRST to get token, then call other endpoints
      checkSiteStatus((isAvailable, token) => {
        if (!isAvailable) {
          navigate('/notavailable');
        } else {
          // Now that we have the token, call all API endpoints
          getTestsAvailable(token);
          getBannerMessage(token);
          
          // Get phoenix data if site is available
          getPhoenixData(token, (dataOk) => {
            if (!dataOk) {
              navigate('/notavailable');
            }
            // Otherwise stay on home page and let it render
          });
        }
      });

      setIsInitialized(true);
    };

    initializeApp();
  }, []); // Only run once on mount

  // Handle hash change for navigation (like original)
  useEffect(() => {
    const handleHashChange = () => {
      if (window.location.hash === '#/') {
        if (state.currentStep !== 0) {
          window.location.reload();
        }
      } else if (window.location.hash === '#/testList') {
        if (state.currentStep !== 0) {
          updateState({ 
            selectedTestName: '',
            goingBackwards: true 
          });
        }
      }
    };

    window.addEventListener('hashchange', handleHashChange);
    return () => window.removeEventListener('hashchange', handleHashChange);
  }, [state.currentStep]);

  if (!isInitialized) {
    return <Loading />;
  }

  return (
    <div className="site-constraint">
      <div className="cost-container">
        <p style={{ fontSize: "10px" }}>{state.version_number}</p>
        <p>{state.bannerMessage}</p>
        <h2>
          Estimate my cost {state.currentStep > 1 ? state.CurrentTestName : ''}
        </h2>
        <Routes>
          <Route path="/" element={<TestCategory />} />
          <Route path="/testList" element={<TestList />} />
          <Route path="/personalInformation" element={<PersonalInfo />} />
          <Route path="/insuranceInformation" element={<InsuranceInfo />} />
          <Route path="/loading-estimate" element={<LoadingEstimate />} />
          <Route path="/estimate" element={<Estimate />} />
          <Route path="/followup" element={<FollowUp />} />
          <Route path="/finalconfirmation" element={<FinalConfirmation />} />
          <Route path="/notavailable" element={<NotAvailable />} />
        </Routes>
      </div>
    </div>
  );
}

function App() {
  return (
    <Router>
      <AppContent />
      <EnvIndicator />
    </Router>
  );
}

export default App;
