import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import path from 'path';

const projectRoot = process.cwd();

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        path.join(projectRoot, 'vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php'),
        path.join(projectRoot, 'storage/framework/views/*.php'),
        path.join(projectRoot, 'resources/views/**/*.blade.php'),
        path.join(projectRoot, 'Modules/**/*.blade.php'),
        path.join(projectRoot, 'Modules/**/resources/views/**/*.blade.php'),
    ],

    darkMode: 'class',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    dark: '#08110e',
                    navbar: '#0b1512',
                    card: '#0f1f1a',
                    primary: '#0E5E4A',
                    secondary: '#10231C',
                    amber: '#F2A900',
                    highlight: '#F2A900',
                    surface: '#08110e',
                    light: '#08110e',
                },
            },
        },
    },
    plugins: [forms],
};

