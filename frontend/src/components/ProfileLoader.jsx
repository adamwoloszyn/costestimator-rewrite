import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import { config } from '../utils/api';
import testProfiles from '../data/TestProfiles';

function ProfileLoader() {
  const { updatePersonalInfo, updateInsuranceInfo, updateState } = useAppContext();
  const navigate = useNavigate();
  const [selectedProfile, setSelectedProfile] = useState('');
  const [showJsonInput, setShowJsonInput] = useState(false);
  const [jsonInput, setJsonInput] = useState('');
  const [error, setError] = useState('');

  // Only show in development
  if (!config.isDevelopment) {
    return null;
  }

  const loadProfile = (profileKey) => {
    const profile = testProfiles[profileKey];
    if (!profile) {
      setError('Profile not found');
      return;
    }

    // Load personal info
    Object.entries(profile.personalInfo).forEach(([key, value]) => {
      updatePersonalInfo(key, value);
    });

    // Load insurance info
    Object.entries(profile.insuranceInfo).forEach(([key, value]) => {
      updateInsuranceInfo(key, value);
    });

    // Load test selection
    updateState({
      phoenixTestId: profile.testSelection.phoenixTestId,
      CurrentTestName: profile.testSelection.CurrentTestName
    });

    setError('');
    alert(`Profile "${profile.name}" loaded successfully! Navigate through the form to see the data.`);
  };

  const handleProfileSelect = (e) => {
    const profileKey = e.target.value;
    setSelectedProfile(profileKey);
    if (profileKey) {
      loadProfile(profileKey);
    }
  };

  const loadFromJson = () => {
    try {
      const profile = JSON.parse(jsonInput);
      
      // Validate structure
      if (!profile.personalInfo || !profile.insuranceInfo) {
        setError('Invalid profile format. Must have personalInfo and insuranceInfo objects.');
        return;
      }

      // Load personal info
      Object.entries(profile.personalInfo).forEach(([key, value]) => {
        updatePersonalInfo(key, value);
      });

      // Load insurance info
      Object.entries(profile.insuranceInfo).forEach(([key, value]) => {
        updateInsuranceInfo(key, value);
      });

      // Load test selection if provided
      if (profile.testSelection) {
        updateState({
          phoenixTestId: profile.testSelection.phoenixTestId || '',
          CurrentTestName: profile.testSelection.CurrentTestName || ''
        });
      }

      setError('');
      setJsonInput('');
      setShowJsonInput(false);
      alert('Custom profile loaded successfully!');
    } catch (err) {
      setError('Invalid JSON format: ' + err.message);
    }
  };

  const exportCurrentProfile = () => {
    // This would need access to current state - you might want to add a button in context
    const template = {
      name: "Custom Profile",
      personalInfo: {},
      insuranceInfo: {},
      testSelection: {}
    };
    
    const jsonString = JSON.stringify(template, null, 2);
    navigator.clipboard.writeText(jsonString);
    alert('Profile template copied to clipboard!');
  };

  return (
    <div className="profile-loader" style={{ 
      padding: '20px', 
      border: '1px solid #ddd', 
      borderRadius: '8px',
      marginBottom: '20px',
      backgroundColor: '#f9f9f9'
    }}>
      <h3 style={{ marginTop: 0 }}>Quick Profile Loader</h3>
      
      <div style={{ marginBottom: '15px' }}>
        <label htmlFor="profile-select" style={{ display: 'block', marginBottom: '5px', fontWeight: 'bold' }}>
          Load Test Profile:
        </label>
        <select 
          id="profile-select"
          value={selectedProfile} 
          onChange={handleProfileSelect}
          style={{ 
            width: '100%', 
            padding: '8px', 
            borderRadius: '4px',
            border: '1px solid #ccc'
          }}
        >
          <option value="">-- Select a profile --</option>
          {Object.entries(testProfiles).map(([key, profile]) => (
            <option key={key} value={key}>{profile.name}</option>
          ))}
        </select>
      </div>

      <div style={{ marginBottom: '15px' }}>
        <button
          onClick={() => setShowJsonInput(!showJsonInput)}
          style={{
            padding: '8px 16px',
            backgroundColor: '#007bff',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer',
            marginRight: '10px'
          }}
        >
          {showJsonInput ? 'Hide' : 'Load'} Custom JSON
        </button>
        
        <button
          onClick={exportCurrentProfile}
          style={{
            padding: '8px 16px',
            backgroundColor: '#28a745',
            color: 'white',
            border: 'none',
            borderRadius: '4px',
            cursor: 'pointer'
          }}
        >
          Copy Template
        </button>
      </div>

      {showJsonInput && (
        <div style={{ marginBottom: '15px' }}>
          <label htmlFor="json-input" style={{ display: 'block', marginBottom: '5px', fontWeight: 'bold' }}>
            Paste JSON Profile:
          </label>
          <textarea
            id="json-input"
            value={jsonInput}
            onChange={(e) => setJsonInput(e.target.value)}
            placeholder='{"name": "Profile Name", "personalInfo": {...}, "insuranceInfo": {...}}'
            style={{
              width: '100%',
              minHeight: '150px',
              padding: '8px',
              borderRadius: '4px',
              border: '1px solid #ccc',
              fontFamily: 'monospace',
              fontSize: '12px'
            }}
          />
          <button
            onClick={loadFromJson}
            style={{
              marginTop: '10px',
              padding: '8px 16px',
              backgroundColor: '#007bff',
              color: 'white',
              border: 'none',
              borderRadius: '4px',
              cursor: 'pointer'
            }}
          >
            Load JSON Profile
          </button>
        </div>
      )}

      {error && (
        <div style={{ 
          padding: '10px', 
          backgroundColor: '#f8d7da', 
          color: '#721c24',
          borderRadius: '4px',
          marginTop: '10px'
        }}>
          {error}
        </div>
      )}

      <div style={{ 
        marginTop: '15px', 
        padding: '10px', 
        backgroundColor: '#d1ecf1', 
        borderRadius: '4px',
        fontSize: '12px',
        color: '#0c5460'
      }}>
        <strong>Note:</strong> Loading a profile will populate the form fields. Navigate through the steps to see the pre-filled data.
      </div>
    </div>
  );
}

export default ProfileLoader;
