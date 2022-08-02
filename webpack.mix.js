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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css')
//     .sourceMaps();

mix.copy('resources/sources', 'public/sources');
mix.copy('resources/images', 'public/images');
mix.js('resources/js/app.js', 'public/js/')
    .js('resources/js/bootstrap.js', 'public/js/')
    .js('resources/js/jquery-3.6.0.min.js', 'public/js/')
    .sass('resources/scss/admin/style.scss', 'public/css/admin')
    .version();
