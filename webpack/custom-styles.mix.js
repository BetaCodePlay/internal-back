const mix = require('laravel-mix');

mix.styles([
        'resources/assets/back/css/unify-admin.css',
        'resources/assets/back/css/admin-icons.css',
        'resources/assets/back/css/custom.css',
        'resources/assets/back/css/datatable-responsive.css',
    ],
    'public/back/css/custom.min.css');
