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
                    primary: '#FB4C4B',
                    secondary: '#191C47',
                },
            },
        },
    },
    plugins: [forms],
};

