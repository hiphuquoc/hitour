import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

/* export style */
export default defineConfig({
  plugins: [
    laravel({
        input: [
            'resources/sources/main/style.scss', 
            'resources/sources/admin/style.scss'
        ],
        refresh: true
    }),
  ]
});