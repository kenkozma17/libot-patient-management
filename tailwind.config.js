import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                'poppins': ['Poppins', 'sans-serif'],
            },
            colors: {
                'main-gray': '#373737',
                'light-gray': '#F0F0F0',
                'dark-green': '#71A370',
                'dark-red': '#890104'
            }
        },
    },

    plugins: [forms, typography],
};
