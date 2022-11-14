const mix = require('laravel-mix');

mix.scripts([
        'node_modules/jszip/dist/jszip.js',
        'resources/assets/back/plugins/hs/js/hs.core.js',
        'resources/assets/back/plugins/hs/js/hs.dropdown.js',
        'resources/assets/back/plugins/hs/js/hs.scrollbar.js',
        'resources/assets/back/js/sidebar.js',
        'resources/assets/back/js/template.js'
    ],
    'public/back/js/scripts.min.js');
