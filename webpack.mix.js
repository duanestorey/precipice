let mix = require('laravel-mix');

mix.js( 'src/js/precipice-admin.js', 'dist' ).setPublicPath( 'dist' )
mix.sass( 'src/scss/precipice-admin.scss', 'dist' );
