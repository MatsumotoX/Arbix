let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Assets Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/views/preferences/app.js', 'public/js/views/preferences')
    .js('resources/js/views/properties/create.js', 'public/js/views/properties')
    .js('resources/js/views/properties/index.js', 'public/js/views/properties')
    .js('resources/js/views/properties/show.js', 'public/js/views/properties')
    .js('resources/js/views/hrs/customers/view.js', 'public/js/views/hrs/customers')
    .js('resources/js/views/lines/index.js', 'public/js/views/lines')
    .js('resources/js/views/lines/createRichMenu.js', 'public/js/views/lines')
    .js('resources/js/views/landing/index.js', 'public/js/views/landing')
    .js('resources/js/app.js', 'public/js/views')
    .extract(['vue', 'bootstrap'])
    .sass('resources/sass/app.scss', 'public/css', {
        includePaths: [
            path.resolve(__dirname, './resources/sass/navigation'),
            path.resolve(__dirname, './resources/sass/dotIndicator')
        ]
    })
    .sass('resources/sass/landing.scss', 'public/css', {
        includePaths: [
            path.resolve(__dirname, './resources/sass/landingPage')
        ]
    })
    .sass('resources/sass/sgrid.scss', 'public/css', {
        implementation: require('node-sass'),
        includePaths: [
            path.resolve(__dirname, './node_modules/@syncfusion/')
        ]
    })
    .scripts([
        'resources/js/loader/loader.js',
        'resources/js/navigation/jquery.menu-aim.js',
        'resources/js/navigation/main.js',
        'resources/js/navigation/modernizr.js',
        'resources/js/property.js',
    ], 'public/js/navigation-loader.js')
    .scripts([
        'resources/js/landingpage/jquery-2.1.4.js',
        'resources/js/landingpage/main.js',
        'resources/js/landingpage/modernizr.js',
    ], 'public/js/landingPage.js')
    .styles([
        'resources/css/loader/loader.css',
        'resources/css/theme/theme/css/style.css',
        'resources/css/theme/mobirise/css/mbr-additional.css',
        'resources/css/bulma/bulma.css',
        'resources/css/syncfusion/inputs/material.css'
    ], 'public/css/theme.css');


