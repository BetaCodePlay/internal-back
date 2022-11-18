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
        'node_modules/animsition/dist/css/animsition.css',
        'node_modules/animate/animate.css',
        'resources/assets/auth/css/main.css',
        'resources/assets/auth/css/util.ccs'
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
        'resources/assets/auth/js/main.js',
        'resources/assets/auth/js/auth.js',
    ],
    'public/auth/js/custom.min.js')
    .extract()
    .version();
