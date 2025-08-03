import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Core app assets
                'resources/css/app.css',
                'resources/js/app.js',
                // Portal-specific assets
                'resources/css/portal.css',
                'resources/js/portal.js',
                // Auth assets
                'resources/css/auth.css',
                'resources/js/auth.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['lodash', 'axios'],
                    portal: ['resources/js/portal.js'],
                    auth: ['resources/js/auth.js'],
                }
            }
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@import "resources/css/variables.scss";`
            }
        }
    }
});
