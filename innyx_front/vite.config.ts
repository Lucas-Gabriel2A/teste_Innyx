import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0', 
    port: 5173,
    strictPort: true,
    proxy: {
      '/api': {
        target: 'http://backend:8000', 
        changeOrigin: true,
        rewrite: (path) => path.replace(/^\/api/, ''),
      },
      '/storage': {
        target: 'http://backend:8000',
        changeOrigin: true,
      },
    },
  },
})
