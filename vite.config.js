import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // Tailwind CSS v4 Plugin - Dark mode otomatis support class-based
        tailwindcss(),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});