import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import react from "@vitejs/plugin-react";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/tailwind.css", "resources/js/react.jsx"],
            refresh: true,
        }),
        react(),
    ],
});
