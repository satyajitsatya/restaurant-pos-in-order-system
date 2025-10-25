// /** @type {import('tailwindcss').Config} */
// export default {
//     content: [
//         "./resources/**/*.blade.php",
//         "./resources/**/*.js",
//         "./public/**/*.html",
//     ],
//     theme: {
//         extend: {},
//     },
//     plugins: [],
// };

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./public/**/*.html",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    50: "#fef2f2",
                    500: "#ef4444",
                    600: "#dc2626",
                    700: "#b91c1c",
                },
                orange: {
                    50: "#fff7ed",
                    100: "#ffedd5",
                    200: "#fed7aa",
                    300: "#fdba74",
                    400: "#fb923c",
                    500: "#f97316",
                    600: "#ea580c",
                    700: "#c2410c",
                    800: "#9a3412",
                    900: "#7c2d12",
                },
                gray: {
                    50: "#f9fafb",
                    100: "#f3f4f6",
                    200: "#e5e7eb",
                    300: "#d1d5db",
                    400: "#9ca3af",
                    500: "#6b7280",
                    600: "#4b5563",
                    700: "#374151",
                    800: "#1f2937",
                    900: "#111827",
                },
            },
            fontFamily: {
                sans: [
                    "Inter",
                    "ui-sans-serif",
                    "system-ui",
                    "-apple-system",
                    "BlinkMacSystemFont",
                    "Segoe UI",
                    "Roboto",
                    "Helvetica Neue",
                    "Arial",
                    "Noto Sans",
                    "sans-serif",
                ],
            },
            boxShadow: {
                sm: "0 1px 2px 0 rgb(0 0 0 / 0.05)",
                md: "0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)",
                lg: "0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)",
                xl: "0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)",
                "2xl": "0 25px 50px -12px rgb(0 0 0 / 0.25)",
            },
            borderRadius: {
                lg: "0.5rem",
                xl: "0.75rem",
                "2xl": "1rem",
            },
        },
    },
    plugins: [require("@tailwindcss/forms")],
};
