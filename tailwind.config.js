/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./storage/framework/views/*.php", // Jika menggunakan Livewire cache
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}