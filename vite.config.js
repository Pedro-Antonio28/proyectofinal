import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true, // Esto refresca Livewire automáticamente
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
