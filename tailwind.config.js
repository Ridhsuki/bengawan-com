/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'brand-blue': '#2D4CC8',
                'brand-dark': '#1e3a8a',
                'brand-gray': '#5A5A5A',
                'footer-bg': '#E5E5E5',
                'input-bg': '#F3F4F6',
                'placeholder-gray': '#6B6B6B'
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
            }
        }
    },
    plugins: [],
}

