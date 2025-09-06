/** @type {import('tailwindcss').Config} */
export const content = [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
];
export const theme = {
    extend: {
        colors: {
            'brand': {
                'orange': '#FF6701',
                'blue': '#003686',
                'orange-50': '#fff7ed',
                'orange-100': '#ffedd5',
                'orange-500': '#FF6701',
                'orange-600': '#ea5a00',
                'orange-700': '#d14d00',
                'blue-50': '#eff6ff',
                'blue-100': '#dbeafe',
                'blue-800': '#1e3a8a',
                'blue-900': '#003686',
            }
        }
    },
};
export const plugins = [];