/** @type {import('tailwindcss').Config} */
module.exports = {
    daisyui: {
        themes: [ "wireframe"],
    },
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'navbar-base': '#094195',
                'c-gray': '#e5e5e5',
                'colorborder':'#a3a3a3',
                'c-orange':'#E5BF51',
                'b-orange':'#F8B800',
                'orange-fce':'#FCE9B8',
                'gray-eee':'#EEEEEE',
                'gray-8484':'#848484',
                'gray-d1d1':'#d1d1d1',
                'black-3d':'#3d3d3d',
                'br-f0e':'#F0ECE4',

                'cu-blue':'#5161CE',
                'cu-blblack':'#35326f',
                'cu-light':'#F0F6FB',
                'cu-gray':'#716d87',
            },
        },
    },
    plugins: [require("daisyui"),require('@tailwindcss/typography')],
}
