import { createRoot } from 'react-dom/client'
import { AppProvider } from './context/AppContext.jsx'
import App from './App.jsx'

console.log('[CostEstimator] Build loaded — 2026-05-22')

createRoot(document.getElementById('root')).render(
  <AppProvider>
    <App />
  </AppProvider>,
)
