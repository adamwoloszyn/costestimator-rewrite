// Test script to verify trading partners data structure
const test = async () => {
  try {
    // Get token first
    console.log('Getting token...');
    const tokenResponse = await fetch('http://localhost:8001/site_status_controller.php');
    const tokenData = await tokenResponse.json();
    const token = tokenData.token;
    
    console.log('Token:', token);
    
    // Get trading partners
    console.log('\nFetching trading partners...');
    const tradingResponse = await fetch('http://localhost:8001/trading_partner_controller.php', {
      headers: {
        'X-CSRF-Token': token,
        'Accept': 'application/json'
      },
      credentials: 'include'
    });
    
    const tradingData = await tradingResponse.json();
    
    console.log('\nAPI Response:', tradingData);
    
    if (Array.isArray(tradingData)) {
      console.log('\nFirst 3 trading partners:');
      tradingData.slice(0, 3).forEach((provider, index) => {
        console.log(`\nProvider ${index + 1}:`);
        console.log('  id:', provider.id);
        console.log('  tp_id:', provider.tp_id);
        console.log('  phoenix_payer_id:', provider.phoenix_payer_id);
        console.log('  tp_name:', provider.tp_name);
        console.log('  displayName:', provider.displayName);
        console.log('  phxPayerId:', provider.phxPayerId);
      });
      
      // Look for provider 449 (United Healthcare)
      const uhc = tradingData.find(p => p.id === "449");
      if (uhc) {
        console.log('\nUnited Healthcare (449) data:');
        console.log('  id:', uhc.id);
        console.log('  tp_id:', uhc.tp_id);
        console.log('  phoenix_payer_id:', uhc.phoenix_payer_id);
        console.log('  tp_name:', uhc.tp_name);
        console.log('  displayName:', uhc.displayName);
      } else {
        console.log('\nProvider 449 not found');
      }
    } else {
      console.log('\nError: API returned:', tradingData);
    }
    
  } catch (error) {
    console.error('Error:', error);
  }
};

test();