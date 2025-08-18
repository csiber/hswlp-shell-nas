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
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#f5f7fb',
                    100: '#e9edf7',
                    200: '#cfd9ee',
                    300: '#a7bce0',
                    400: '#7a9ed0',
                    500: '#4f82c2',   // primer (kékes)
                    600: '#386aab',
                    700: '#2e558a',
                    800: '#244369',
                    900: '#1d3654',
                }
            },
            borderRadius: { '2xl': '1rem' },
            boxShadow: { 'card': '0 4px 18px rgba(0,0,0,0.08)' }
        }
    },
    plugins: [forms, typography],
};
