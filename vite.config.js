import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { watch } from 'vite-plugin-watch';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css','resources/js/app.js'],
      refresh: true,
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false,
        },
      },
    }),
    watch({
      pattern: 'routes/*.php',
      command: 'php artisan ziggy:generate',
    }),
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources/js'),
      'ziggy-js': path.resolve(__dirname, 'vendor/tightenco/ziggy'),
    },
  },
  server: {
    host: '127.0.0.1',  // Add this to force IPv4 only
},
});
