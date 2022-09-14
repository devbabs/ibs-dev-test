import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        // https: true,
        host: 'ibs-dev.test',
        https: {
            key: fs.readFileSync('ibs-dev.test-key.pem'),
            cert: fs.readFileSync('ibs-dev.test.pem'),
        },
        hmr: {
            host: 'ibs-dev.test',
        },
    }
});
