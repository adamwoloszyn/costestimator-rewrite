# Environment Configuration Guide

## Overview
This project uses Vite's environment variable system to manage different deployment targets (development, staging, production).

## Environment Files

- `.env.development` - Local development (default)
- `.env.staging` - Staging/QA environment
- `.env.production` - Production (LabCorp)
- `.env.production.legacy` - Legacy production (Integrated Genetics)
- `.env.example` - Template file (committed to git)

**Note:** Actual `.env` files are gitignored for security.

## Available Commands

### Development
```bash
npm run dev                    # Local development (developmenthost:8001)
npm run dev:staging           # Dev mode with staging endpoints
npm run dev:production        # Dev mode with production endpoints
```

### Build
```bash
npm run build                 # Build for development
npm run build:staging         # Build for staging deployment
npm run build:production      # Build for production (LabCorp)
npm run build:production-legacy  # Build for legacy production (Integrated Genetics)
```

### Preview
```bash
npm run preview               # Preview production build developmently
npm run preview:staging       # Preview staging build
npm run preview:production    # Preview production build
```

## Environment Variables

### Available Variables
- `VITE_APP_ENV` - Environment name (development/staging/production)
- `VITE_API_BASE_URL` - Base URL for API endpoints
- `VITE_IMAGE_SOURCE` - Base URL for static images

### Usage in Code

```javascript
// In any component or module:
import { config } from './utils/api';

console.log('Environment:', config.APP_ENV);
console.log('API Base:', config.API_BASE_URL);
console.log('Is Production:', config.isProduction);
```

## Deployment Workflow

### Staging Deployment
```bash
npm run build:staging
# Upload dist/ to staging server
```

### Production Deployment
```bash
npm run build:production
# Upload dist/ to production server
```

## Adding New Environments

1. Create new `.env.[name]` file
2. Add environment variables
3. Add build script in `package.json`:
   ```json
   "build:newenv": "vite build --mode newenv"
   ```

## Security Notes

- Never commit actual `.env` files (except `.env.example`)
- Keep `.env.example` updated with all required variables
- Use `.env.development` for development overrides (gitignored)
- Environment variables prefixed with `VITE_` are exposed to client-side code

## Current Endpoint Configuration

### Local (default)
- API: `http://developmenthost:8001`
- Images: `https://ce.integratedgenetics.com/ce`

### Staging
- API: `https://costestimator.tronestaging.com/cost-estimator/php`
- Images: `https://costestimator.tronestaging.com/cost-estimator`

### Production (LabCorp)
- API: `https://costestimator.labcorp.com/cost-estimator/php`
- Images: `https://costestimator.labcorp.com/cost-estimator`

### Production Legacy (Integrated Genetics)
- API: `https://ce.integratedgenetics.com/ce/php`
- Images: `https://ce.integratedgenetics.com/ce`
