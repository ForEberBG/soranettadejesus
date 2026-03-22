const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('postcss-import'),
       require('tailwindcss'), // si quieres usar Tailwind, si no puedes quitarlo
       require('autoprefixer'),
   ])
   .setPublicPath('public');
