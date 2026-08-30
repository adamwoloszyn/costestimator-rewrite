import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAppContext } from '../context/AppContext';
import ProfileLoader from '../components/ProfileLoader';
import { config } from '../utils/api';

const imageSourceEndpoint = config.IMAGE_SOURCE;

function TestCategory() {
  const { state, updateState } = useAppContext();
  const navigate = useNavigate();

  const selectTestCategory = (route, index, categoryCode, actualCategoryName) => {
    // Get the test category data from staticTestCategories
    const selectedCategory = state.staticTestCategories[index];
    
    // Update state with selected category
    updateState({
      currentTestList: selectedCategory?.tests || [],  // Get tests from staticTestCategories
      testCategorySelectedIndex: index,
      currentTestCode: categoryCode,
      selectedTestCategoryName: categoryCode,
      actualTestCategoryName: actualCategoryName,
      currentStep: state.currentStep + 1
    });
    
    // Navigate to test list
    navigate(route);
  };

  useEffect(() => {
    // Show the test categories after initial load (DOM manipulation like original)
    const testCategoriesLoading = document.querySelector('#testCategoriesLoading');
    const testCategories = document.querySelector('#testCategories');

    if (testCategoriesLoading && testCategories && state.staticTestCategories.length > 0) {
      testCategoriesLoading.classList.add('hidden');
      testCategories.classList.remove('hidden');
    }
  }, [state.staticTestCategories]);

  return (
    <>
      <ProfileLoader />
      
      <article key={1} className="cost-container__content">
        <header>
          <p style={{ color: '#3A5CE9' }}>
            If you have received a bill or explanation of benefits (EOB) please contact us at 844.799.3243
          </p>
          <h4 className="desktop-msg">The type of test I want to get an estimate for is:</h4>
          <h4 className="mobile-msg">The type of test:</h4>
        </header>
        <section>
          <div id="testCategoriesLoading" className="cost-waiting">
            <h3 className="cost-blue-alert">Cost Estimator Loading...</h3>
            <div className="cost-loading-container">
              <img src={`${imageSourceEndpoint}/assets/images/Loading_icon.gif`} alt="" />
            </div>
          </div>
          <div id="testCategories" className="cost-selectors hidden">
            {Object.keys(state.staticTestCategories).map((key) => {
              const category = state.staticTestCategories[key];
              return (
                <button
                  key={key}
                  onClick={() => selectTestCategory(
                    '/testList',
                    key,
                    category.category_code,
                    category.category_code
                  )}
                  className="cost-selector"
                >
                  <img
                    src={`${imageSourceEndpoint.replace(/\/$/, '')}/${category.imageUrl.replace(/^\//, '')}`}
                    className={category.color}
                    alt={category.displayName}
                  />
                  <h3>{category.displayName}</h3>
                  {category.description}
                </button>
              );
            })}
          </div>
        </section>
      </article>
      <h4 key={2}>
        Si usted habla español, comuníquese con un miembro de nuestro equipo llamando al 844.799.3243
      </h4>
    </>
  );
}

export default TestCategory;
