import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import { useEffect } from 'react';

function TestList() {
  const { state, updateState, updatePersonalInfo } = useAppContext();
  const navigate = useNavigate();

  // Route guard: redirect if no test category has been selected
  useEffect(() => {
    if (!state.currentTestList || state.currentTestList.length === 0) {
      navigate('/');
    }
  }, [state.currentTestList, navigate]);

  const goBack = () => {
    navigate(-1);
  };

  // Search array for test ID (from old App.js)
  const searchArrayForTestId = (array, testId) => {
    for (let i = 0; i < array.length; i++) {
      const node = array[i];
      
      // Check if the current node has the desired test ID
      if (node.tests) {
        for (let j = 0; j < node.tests.length; j++) {
          const test = node.tests[j];
          if (test.id == testId) {
            return test; // Return the node with the matching test ID
          }
        }
      }
      
      // If the current node has nested objects, recursively search within them
      if (node.tests && node.tests.length > 0) {
        const result = searchArrayForTestId(node.tests, testId);
        if (result) {
          return result; // Return the node if found in the nested objects
        }
      }
    }
    
    return null; // Return null if the test ID is not found in the array
  };

  const selectTest = (path, parentIndex, testIndex, testCode, index, headerDisplay, testCategoryName, testNameForSF) => {
    updatePersonalInfo('Member_State', '');
    const node = searchArrayForTestId(state.testCategories, testIndex);
    
    updateState({
      phoenixTestId: testIndex,
      actualTestCategoryName: testCategoryName,
      selectedTestName: headerDisplay,
      selectedTestNameForSF: testNameForSF,
      currentTestQuestions: node?.questions || [],
      testSelectedIndex: 0,
      currentTestCode: testCode,
      currentTestIndex: testIndex,
      currentParentIndex: parentIndex,
      currentStep: state.currentStep + 1,
      CurrentTestName: 'for ' + headerDisplay,
      Member_State: ''
    });
    
    navigate(path);
  };

  const selectedTestCategoryName = state.selectedTestCategoryName;

  return (
    <article className="cost-container__content">
      <header>
        {selectedTestCategoryName === "Pre-pregnancy" || selectedTestCategoryName === "Inheritest"
          ? <h4>Carrier Screening</h4>
          : <h4>Prenatal cell-free DNA screening</h4>
        }
        <a className="cost-back-btn" onClick={goBack}>
          <i className="fa fa-arrow-left" aria-hidden="true"></i>Back
        </a>
      </header>
      <section>
        <div className="cost-service-list">
          {/* NIPT Tests */}
          {state.currentTestList.map((test, index) => {
            if ((selectedTestCategoryName === "Pregnancy" || selectedTestCategoryName === "NIPT") && test.categoryName === "NIPT") {
              return (
                <a 
                  key={index} 
                  onClick={() => selectTest(
                    '/personalInformation',
                    test.parentIndex,
                    test.indexInDynamicData,
                    test.testCode,
                    index,
                    test.headerDisplay,
                    test.categoryName,
                    test.testName
                  )}
                >
                  <h3>{test.displayName}</h3>
                  <p>{test.test_description}</p>
                </a>
              );
            }
            return null;
          })}
          
          {/* Carrier Screening Header */}
          {(selectedTestCategoryName === "Pregnancy" || selectedTestCategoryName === "NIPT") && (
            <header><h4>Carrier Screening</h4></header>
          )}
          
          {/* Inheritest Tests */}
          {state.currentTestList.map((test, index) => {
            if (test.categoryName === "Inheritest") {
              return (
                <a 
                  key={index} 
                  onClick={() => selectTest(
                    '/personalInformation',
                    test.parentIndex,
                    test.indexInDynamicData,
                    test.testCode,
                    index,
                    test.headerDisplay,
                    test.categoryName,
                    test.testName
                  )}
                >
                  <h3>{test.displayName}</h3>
                  <p>{test.test_description}</p>
                </a>
              );
            }
            return null;
          })}
        </div>
      </section>
    </article>
  );
}

export default TestList;
