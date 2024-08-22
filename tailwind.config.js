import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            // Theme from node_modules\primevue\resources\themes\aura-light-indigo\theme.css
            colors: {
                primary: {
                    ...colors.indigo,
                    DEFAULT: 'var(--primary-color)',
                },
                secondary: 'var(--text-color-secondary)',
                neutral: {
                    ...colors.gray,
                    DEFAULT: colors.gray['100']
                },
                error: {
                    ...colors.red,
                    DEFAULT: colors.red['600']
                },
                link: {
                    ...colors.blue,
                    DEFAULT: colors.blue['800']
                },
                success: {
                    ...colors.emerald,
                    DEFAULT: colors.emerald['600']
                },
                inputError: '#f87171',
                surfaceInput: 'var(--surface-400)',
                surfaceHover: 'var(--surface-hover)',
                surfaceBorder: 'var(--surface-border)',
                surfaceCard: 'var(--surface-card)',
            }
        },
    },

    plugins: [forms],
};
