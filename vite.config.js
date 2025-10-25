// import { defineConfig } from 'vite';
// import laravel from 'laravel-vite-plugin';

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: [
//                 'resources/sass/app.scss',
//                 'resources/js/app.js',
//             ],
//             refresh: true,
//         }),
//     ],
// });

// import { defineConfig } from "vite";
// import laravel from "laravel-vite-plugin";

// export default defineConfig({
//     plugins: [
//         laravel({
//             input: ["resources/css/app.css", "resources/js/app.js"],
//             refresh: true,
//         }),
//     ],
//     build: {
//         sourcemap: true,
//     },
//     // Fix source map issues in development
//     esbuild: {
//         sourcemap: "linked",
//     },
//     // Optimize dependency pre-bundling
//     optimizeDeps: {
//         force: true, // Force re-optimization of dependencies
//     },
//     // Server configuration
//     server: {
//         sourcemapIgnoreList: false,
//         fs: {
//             strict: false,
//         },
//     },
// });

import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig(({ command, mode }) => {
    const isProduction = command === "build";

    return {
        plugins: [
            laravel({
                input: ["resources/css/app.css", "resources/js/app.js"],
                refresh: true,
            }),
        ],
        build: {
            sourcemap: false, // Disable source maps for production
            minify: "esbuild",
            rollupOptions: {
                output: {
                    manualChunks: undefined,
                },
            },
        },
        esbuild: {
            // Remove conflicting source map configuration
            drop: isProduction ? ["console", "debugger"] : [],
        },
        css: {
            devSourcemap: false,
        },
        optimizeDeps: {
            esbuildOptions: {
                // Remove source map configuration that causes conflicts
                target: "es2020",
            },
        },
        server: {
            hmr: {
                overlay: false,
            },
        },
    };
});
