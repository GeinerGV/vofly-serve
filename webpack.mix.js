const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.react('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css');
mix.sass('resources/sass/vendor.scss', 'public/css');
mix.sass('resources/sass/home.scss', 'public/css/page');
mix.js("resources/js/home/dashboard.js", "public/js/");
mix.js("resources/js/home/tables.js", "public/js/");

mix.version();
