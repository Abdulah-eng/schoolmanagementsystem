import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                'resources/js/components/welcome-modal.js',
                'resources/js/student/dropdowns.js',
                'resources/js/student/toast.js',
                'resources/js/student/modal.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
