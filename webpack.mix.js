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
mix.sass('resources/sass/track.scss', 'public/css/page');
mix.js("resources/js/home/dashboard.js", "public/js/");
mix.js("resources/js/home/tables.js", "public/js/");
mix.js("resources/js/track.js", "public/js/");

mix.react("resources/js/components/Table.jsx", "public/js/components");
mix.react("resources/js/components/Pagos.jsx", "public/js/components");
mix.react("resources/js/components/Drivers.jsx", "public/js/components");
mix.react("resources/js/components/Usuarios.jsx", "public/js/components");
mix.react("resources/js/components/Pedidos.jsx", "public/js/components");
mix.js("resources/js/funciones.js", "public/js/");

mix.babelConfig({"plugins": ["@babel/plugin-proposal-class-properties"]});

if (mix.inProduction()) {
	mix.version();
} else {
	mix.sourceMaps(false);
}

