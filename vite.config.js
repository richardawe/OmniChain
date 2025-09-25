import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',
                'resources/js/driver-app.js'
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    build: {
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
        rollupOptions: {
            input: {
                app: 'resources/js/app.js',
                driver: 'resources/js/driver-app.js'
            }
        }
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
