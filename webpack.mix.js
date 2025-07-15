// const mix = require('laravel-mix');

// /*
//  |--------------------------------------------------------------------------
//  | Mix Asset Management
//  |--------------------------------------------------------------------------
//  |
//  | Mix provides a clean, fluent API for defining some Webpack build steps
//  | for your Laravel application. By default, we are compiling the Sass
//  | file for the application as well as bundling up all the JS files.
//  |
//  */

// mix.setPublicPath('public')
//     .setResourceRoot('../') // Turns assets paths in css relative to css file
//     .vue()
//     .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
//     .sass('resources/sass/backend/app.scss', 'css/backend.css')
//     .js('resources/js/frontend/app.js', 'js/frontend.js')
//     .js('resources/js/backend/app.js', 'js/backend.js')
//     .extract([
//         'alpinejs',
//         'jquery',
//         'bootstrap',
//         'popper.js',
//         'axios',
//         'sweetalert2',
//         'lodash'
//     ])
//     .sourceMaps();

// if (mix.inProduction()) {
//     mix.version();
// } else {
//     // Uses inline source-maps on development
//     mix.webpackConfig({
//         devtool: 'inline-source-map'
//     });
// }


const mix = require('laravel-mix');
const webpack = require('webpack');

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

mix.setPublicPath('public')
    .setResourceRoot('../') // Turns assets paths in css relative to css file
    // .vue()
    .sass('resources/sass/frontend/app.scss', 'css/frontend.css')
    .sass('resources/sass/backend/app.scss', 'css/backend.css')
    .js('resources/js/frontend/app.js', 'js/frontend.js')
    .js('resources/js/backend/app.js', 'js/backend.js')
    .extract([
        'alpinejs',
        'jquery',
        'bootstrap',
        'popper.js',
        'axios',
        'sweetalert2',
        'lodash',
        'toastr' // Added toastr to the extract list
    ])
    .sourceMaps()
    .webpackConfig({
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery',
            }),
        ],
        devtool: mix.inProduction() ? false : 'inline-source-map'
    });

if (mix.inProduction()) {
    mix.version();
}
else {
    // Uses inline source-maps on development
    mix.webpackConfig({
        devtool: 'inline-source-map'
    });
}