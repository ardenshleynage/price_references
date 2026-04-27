import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

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

                "resources/js/readers/branches-modal.js",
                "resources/js/readers/categories-modal.js",
                "resources/js/readers/products-modal.js",
                "resources/js/readers/search-modals.js",

                "resources/js/super_admin/branches-modal.js",
                "resources/js/super_admin/categories-modal.js",
                "resources/js/super_admin/search-modals.js",
                "resources/js/super_admin/user-modal.js",
                "resources/js/super_admin/products-modal.js",

                "resources/js/admins/categories-modal.js",
                "resources/js/admins/branches-modal.js",
                "resources/js/admins/products-modal.js",
                "resources/js/admins/search-modals.js",

                "resources/scss/tabs.scss",
            ],
            refresh: true,
        }),
    ],
    server: {
        watch: {
            ignored: ["**/storage/framework/views/**"],
        },
    },
});
