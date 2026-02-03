import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        // Minify assets in production
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Remove console.log in production
            },
        },
        // Optimize chunk splitting
        rollupOptions: {
            output: {
                manualChunks: {
                    // Split vendor code into separate chunk
                    vendor: ['axios'],
                },
            },
        },
        // Set chunk size warning limit
        chunkSizeWarningLimit: 1000,
    },
    // Enable CSS code splitting
    css: {
        devSourcemap: true,
    },
});
