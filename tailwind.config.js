// tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'benin-green': '#008751',
        'benin-yellow': '#FCD116',
        'benin-red': '#E8112D',
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}