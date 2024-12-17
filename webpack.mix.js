const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')  // JavaScript
   .css('resources/css/app.css', 'public/css') // Din custom CSS
   .options({
       processCssUrls: false
   });

if (mix.inProduction()) {
    mix.version(); // Cache-busting for produksjon
}
