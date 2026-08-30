// Direct test of trading partner API
console.log('Testing trading partner API...');

fetch('http://localhost:5173/api/tests-available')
  .then(r => r.json()) 
  .then(d => {
    console.log('Token from tests API:', d.token);
    
    // Now use that token for trading partners
    return fetch('http://localhost:8001/trading_partner_controller.php', {
      headers: {
        'X-CSRF-Token': d.token,
        'Accept': 'application/json'
      },
      credentials: 'include'
    });
  })
  .then(r => r.json())
  .then(data => {
    console.log('Trading partners response type:', typeof data);
    console.log('Is array?', Array.isArray(data));
    if (Array.isArray(data)) {
      console.log('Total providers:', data.length);
      // Find provider 449
      const p449 = data.find(p => p.id === "449");
      console.log('Provider 449:', p449);
      
      // Show first few for structure
      console.log('First 3 providers:', data.slice(0, 3));
    } else {
      console.log('Error response:', data);
    }
  })
  .catch(err => console.error('Error:', err));