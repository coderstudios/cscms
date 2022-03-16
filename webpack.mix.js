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

mix.copy('node_modules/font-awesome/fonts','public/fonts')
mix.copy('node_modules/ckeditor', 'public/js/ckeditor')

mix
    .setPublicPath('public')
    .js('resources/assets/backend/js/app.js', 'public/js/backend')
    .sass('resources/assets/backend/sass/app.scss', 'public/css/backend')
    .copy('resources/assets/backend/img', 'public/img/backend')
    .extract(['dropzone'])
    .sourceMaps()
    .version();
    // .copy('public', '../app/public/vendor/cscms')

mix
    .setPublicPath('public')
    .js('resources/assets/frontend/js/app.js', 'public/js/frontend')
    .sass('resources/assets/frontend/sass/app.scss', 'public/css/frontend')
    .copy('resources/assets/frontend/img', 'public/img/frontend')
    .sourceMaps();
    // .copy('public', '../app/public/vendor/cscms')

//mix.copy('public', '../../coderstudios/cscms/public/vendor/cscms');