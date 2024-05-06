const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | SASS
 |--------------------------------------------------------------------------
 */
mix.sass(
    'resources/assets/back/scss/vendor.scss',
    'public/back/scss/vendor.min.css'
);


/*
 |--------------------------------------------------------------------------
 | CSS
 |--------------------------------------------------------------------------
 */
mix.styles([
        'public/back/scss/vendor.min.css',
        'node_modules/animate.css/animate.css',
        'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css',
        'node_modules/jstree/dist/themes/default/style.css'
    ],
    'public/back/css/vendor.min.css');
