const mix = require('laravel-mix');

mix.copyDirectory('node_modules/tinymce/themes/silver', 'public/back/js/themes/silver');
mix.copyDirectory('node_modules/tinymce/plugins', 'public/back/js/plugins');
mix.copyDirectory('node_modules/tinymce/skins', 'public/back/js/skins');
mix.copyDirectory('node_modules/tinymce/icons', 'public/back/js/icons');
mix.copyDirectory('node_modules/jstree/dist/themes/default/throbber.gif', 'public/back/css');
mix.copyDirectory('node_modules/jstree/dist/themes/default/32px.png', 'public/back/css');
mix.copyDirectory('resources/assets/back/fonts', 'public/back/fonts');
