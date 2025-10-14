import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/checkout.css',
                'resources/js/checkout.js', // ✅ add this
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
