import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            colors: {
                warm: {
                    50: '#FAF8F5',
                    100: '#F5F3F0',
                    200: '#EDEAE4',
                    300: '#E0D9CC',
                    400: '#CFC6B4',
                    500: '#A8A08D',
                    600: '#7E776A',
                    700: '#5C564B',
                    800: '#3B362D',
                    900: '#221F19',
                },
                primary: '#3B82F6',
                accent: '#D97706',
            },
        },
        fontFamily: {
            sans: ['Inter', ...defaultTheme.fontFamily.sans],
            serif: ['Lora', ...defaultTheme.fontFamily.serif],
        },
    },

    plugins: [forms],
};