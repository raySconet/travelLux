import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwind from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css',
              'resources/css/summernote-bs4.css',
               'resources/js/app.js',
               'resources/js/courtCases.js',
               'resources/js/permissions.js',
               'resources/js/summernote-bs4.js',
      ],
      refresh: true,
    }),
    tailwind(),
  ],
});
