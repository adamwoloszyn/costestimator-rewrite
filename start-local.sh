#!/usr/bin/env bash
# Start local dev: PHP backend on :8001 and Vite frontend on :5173
set -e

ROOT="$(cd "$(dirname "$0")" && pwd)"

# Kill anything already on these ports
lsof -ti:8001 | xargs kill -9 2>/dev/null || true
lsof -ti:5173 | xargs kill -9 2>/dev/null || true

echo "Starting PHP backend on http://localhost:8001 ..."
php -S localhost:8001 -t "$ROOT/local-dev" &
PHP_PID=$!

echo "Starting Vite frontend on http://localhost:5173 ..."
cd "$ROOT/frontend" && npm run dev &
VITE_PID=$!

echo ""
echo "  Backend : http://localhost:8001"
echo "  Frontend: http://localhost:5173"
echo ""
echo "Press Ctrl+C to stop both servers."

trap "kill $PHP_PID $VITE_PID 2>/dev/null; echo 'Stopped.'" INT TERM
wait
