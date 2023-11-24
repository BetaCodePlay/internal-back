const mix = require('laravel-mix');

mix.styles([
        'resources/assets/back/css/unify-admin.css',
        'resources/assets/back/css/admin-icons.css',
        'resources/assets/back/css/custom.css',
        'resources/assets/back/scss/template.scss'
    ],
    'public/back/css/custom.min.css');
