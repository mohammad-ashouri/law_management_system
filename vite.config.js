import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from 'tailwindcss';
import basicSSL from '@vitejs/plugin-basic-ssl';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/login.css','resources/js/login.js','resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        // basicSSL(),
    ],
    css: {
        postcss: {
            plugins: [
                tailwindcss('./tailwind.config.js'),
            ],
        },
    },
});
