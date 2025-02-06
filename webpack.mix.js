const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .react()  // Habilita React
    .sass('resources/css/app.css', 'public/css')
    .version();
