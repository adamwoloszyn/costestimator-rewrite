# Cost Estimator Staging Deployment Package

## Overview
This package contains the production-ready Cost Estimator application, rebuilt to match the original estimator behavior exactly.

## Package Contents
```
staging-deploy/
├── frontend/           # Production-built React frontend (Vite)
│   ├── index.html     # Main HTML entry point
│   └── assets/        # Optimized JS/CSS bundles
├── backend/           # PHP backend controllers
│   ├── public/        # Web-accessible PHP files
│   │   ├── phoenix_controller.php
│   │   ├── phoenix_phone_controller.php
│   │   ├── phoenix_lookup_controller.php
│   │   ├── salesforce_controller.php
│   │   ├── chc_controller.php
│   │   └── frontend_response.php
│   └── logs/          # Log directory (empty for staging)
└── README.md          # This file
```

## Deployment Instructions

### 1. Frontend Deployment
Upload the contents of `frontend/` to your web server's public HTML directory:
- Upload `index.html` to root
- Upload `assets/` folder with all files

### 2. Backend Deployment  
Upload the contents of `backend/public/` to your PHP-enabled directory:
- Ensure PHP 7.4+ is available
- Set proper permissions on `logs/` directory (775 or 755)
- Verify all controller endpoints are accessible

### 3. Environment Configuration
All controllers are pre-configured for QA/staging environments:
- Phoenix Controller: qa-new environment
- Other controllers: qa environment
- No production endpoints are used

### 4. Testing Deployment
After upload, test these endpoints:
- Frontend: `http://your-domain.com/` (should load React app)
- Phoenix: `http://your-domain.com/phoenix_controller.php`
- CHC: `http://your-domain.com/chc_controller.php`

## Key Features
✅ Matches original estimator behavior exactly  
✅ Cloudflare Turnstile CAPTCHA integration  
✅ Phoenix QA integration returning real estimates  
✅ Business rules engine with 15+ scenarios  
✅ Cost estimate ID placeholder replacement  
✅ Loading screen matching original design  

## Production Build Stats
- Frontend Bundle: 294.59 kB (gzipped: 83.37 kB)
- CSS Bundle: 0.62 kB (gzipped: 0.31 kB)
- HTML: 0.72 kB (gzipped: 0.40 kB)

## Support
For issues or questions, refer to the original development workspace at:
`/Volumes/Development/2026_Development/ai-agents-hub/projects/costestimator-rewrite/`