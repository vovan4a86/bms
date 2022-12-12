let mix = require('laravel-mix');

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
mix.sourcemaps = false;
mix.browserSync({
    proxy: 'bms.test'
});
mix.js([
    'resources/assets/js--sources/main.js',
    ], 'resources/assets/js/main.js')
    .scripts([
        'resources/assets/js/main.js',
        'resources/assets/js/custom.js',
    ], 'public/static/js/all.js')
    .styles([
        'resources/assets/css/styles.css',
        'resources/assets/css/custom.css'
    ], 'public/static/css/all.css')
    .version();
