const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        fontSize: {
            xs: ["11px", "1.4"],
            sm: ["12px", "1.45"],
            base: ["13px", "1.5"],
            lg: ["15px", "1.5"],
            xl: ["17px", "1.4"],
        },
        extend: {
            fontFamily: {
                sans: [
                    "Inter Tight",
                    "Manrope",
                    "ui-sans-serif",
                    "system-ui",
                ],
            },
            fontWeight: {
                normal: "300",
                title: "600",
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
