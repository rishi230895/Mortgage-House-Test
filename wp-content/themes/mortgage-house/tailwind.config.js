/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    fontFamily: {
        inter: ["Inter", "sans-serif"],
    },
    container: {
        center: true,
        padding: {
            DEFAULT: '1rem'
        }
    },
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms')
  ],
}

