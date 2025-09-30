import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwind from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/css/app.css',
              'resources/css/calendar.css',
              'resources/css/category.css',
               'resources/js/app.js',
               'resources/js/calendar.js',
               'resources/js/category.js',
               'resources/js/courtCases.js',
      ],
      refresh: true,
    }),
    tailwind(),
  ],
});
