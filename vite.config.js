import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                'resources/js/main.js', 
                'resources/js/cart-system.js', 
                'resources/js/product.js', 
                'resources/js/checkout.js', 
                'resources/css/checkout.css'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
