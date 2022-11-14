const mix = require('laravel-mix');

mix.setPublicPath('public/auth/');

/*
|--------------------------------------------------------------------------
| SASS
|--------------------------------------------------------------------------
*/
mix.sass(
    'resources/assets/auth/scss/vendor.scss',
    'public/auth/css/vendor.min.css'
).version();

/*
|--------------------------------------------------------------------------
| CSS
|--------------------------------------------------------------------------
*/
mix.styles([
        'resources/assets/auth/css/languages.css',
        'resources/assets/auth/css/style.css',
        'resources/assets/auth/css/orange.css',
        'resources/assets/auth/css/custom.css',
    ],
    'public/auth/css/custom.min.css')
    .version();

/*
 |--------------------------------------------------------------------------
 | JS
 |--------------------------------------------------------------------------
 */

mix.autoload({
    jquery: ['$', 'jQuery', 'jquery', 'window.jQuery'],
    'popper.js/dist/umd/popper.js': ['Popper']
}).js([
        'resources/assets/auth/js/bootstrap.js',
        'resources/assets/auth/js/custom.js',
        'resources/assets/auth/js/auth.js',
    ],
    'public/auth/js/custom.min.js')
    .extract()
    .version();
