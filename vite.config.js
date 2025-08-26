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
                // Auth assets
                'resources/css/auth.css',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['lodash', 'axios'],
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
