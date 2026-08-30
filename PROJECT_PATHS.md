# CostEstimator Rewrite - Project Path Reference

## Key Directories (for AI Assistant reference)

**Frontend:** `/Volumes/Development/2026_Development/ai-agents-hub/projects/costestimator-rewrite/frontend`
- Dev server: `npm run dev` (runs on port 5173 or 5174)
- Node.js 20.20.0
- Vite 7.3.1

**Backend:** `/Volumes/Development/2026_Development/ai-agents-hub/projects/costestimator-rewrite/backend`  
- Dev server: `php -S localhost:8001 -t public`
- PHP 8.4.7
- Main controllers in `/public/` directory

## Quick Commands

Start both servers:
```bash
# Backend (run in background)
cd /Volumes/Development/2026_Development/ai-agents-hub/projects/costestimator-rewrite/backend && php -S localhost:8001 -t public &

# Frontend  
cd /Volumes/Development/2026_Development/ai-agents-hub/projects/costestimator-rewrite/frontend && npm run dev
```

**IMPORTANT:** Always use full absolute paths to avoid directory navigation errors!