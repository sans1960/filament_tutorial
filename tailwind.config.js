const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: { 
                danger: colors.red,
                primary: colors.green,
                success: colors.blue,
                warning: colors.rose,
            }, 
        },
    },

    plugins: [require('@tailwindcss/forms'),require('@tailwindcss/typography'), ],
};
