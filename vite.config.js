import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],
    server: {
        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        https: false,
        hmr: {
            host: "laravel-ticket-system.ddev.site",
            port: 5174,
            protocol: "wss",
        },
        watch: {
            usePolling: true,
        },
    },
});
