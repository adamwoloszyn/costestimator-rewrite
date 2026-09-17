import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vite.dev/config/
export default defineConfig(({ mode }) => ({
  plugins: [react()],
  server: {
    port: 5173,
    open: true
  },
  base: './', // For hash routing compatibility
  build: mode === 'production' ? {
    rollupOptions: {
      output: {
        // IIFE wraps the whole bundle in its own function scope, so no
        // top-level binding (e.g. the minified React reference) can be
        // read or overwritten by other scripts on the host page (jQuery, etc.)
        format: 'iife',
        entryFileNames: 'assets/index-DWCE_c3l.js',
        chunkFileNames: 'assets/index-DWCE_c3l.js',
        assetFileNames: (assetInfo) => {
          if (assetInfo.name?.endsWith('.css')) return 'assets/index-DKs6eurG.css';
          return 'assets/[name]-[hash][extname]';
        }
      }
    }
  } : {}
}))
