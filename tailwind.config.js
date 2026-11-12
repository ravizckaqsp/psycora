import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50:  '#f5f3ff',
                    100: '#ede9fe',
                    500: '#8b5cf6',
                    600: '#7c3aed',
                    700: '#6d28d9',
                },
            },
            animation: {
                'bounce-slow': 'bounce 1.5s infinite',
            },
            boxShadow: {
                'violet-sm': '0 2px 8px rgba(124, 58, 237, 0.15)',
                'violet-md': '0 4px 16px rgba(124, 58, 237, 0.2)',
            }
        },
    },

    plugins: [forms],
};
