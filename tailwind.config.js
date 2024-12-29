/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      screens: {
        sm: '640px',
        md: '768px',
        lg: '1024px',
        xl: '1280px',
        '2xl': '1536px',

        mac13: { max: '1440px' },
      },
      colors: {
        primary: {
          DEFAULT: '#1D4ED8', // Couleur principale
          light: '#3B82F6',   // Variante plus claire
          dark: '#1E3A8A',    // Variante plus sombre
        },
      },
    },
  },
  plugins: [
    require('flowbite/plugin'),
  ],
}
