import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/css/styles.css",
                "resources/css/form.css",
                "resources/css/home.css",
                "resources/css/login.css",
                "resources/css/tailwind.css",

                "resources/js/app.js",
                "resources/js/bootstrap.js",
                "resources/js/modal.js",
                "resources/js/script.js",
                "resources/js/admins/categories-modal.blade.js",
                "resources/js/admins/branches-modal.blade.js",
                "resources/js/admins/products-modal.blade.js",
                "resources/js/admins/search-modals.blade.js",

                "resources/scss/tabs.scss",
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
