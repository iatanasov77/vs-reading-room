const Encore = require('@symfony/webpack-encore');
const path = require('path');

Encore
    .setOutputPath( 'public/admin-panel/build/custom-entries/' )
    .setPublicPath( '/build/custom-entries/' )
    
    .autoProvidejQuery()
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: true
    })
    .configureFilenames({
        js: '[name].js?[contenthash]',
        css: '[name].css?[contenthash]',
        assets: '[name].[ext]?[hash:8]'
    })
    .enableSingleRuntimeChunk()
    .enableVersioning( Encore.isProduction() )
    .enableSourceMaps( !Encore.isProduction() )
    
    .addAliases({
        '@': path.resolve( __dirname, '../../vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default/assets' )
    })

    // Custom Entries
    /////////////////////////////////////////////////////////////////////////////////////////////////
    .addEntry( 'js/reading-room-settings', './assets/admin-panel/js/pages/reading-room-settings.js' )
    .addEntry( 'js/reading-room-settings-edit', './assets/admin-panel/js/pages/reading-room-settings-edit.js' )
    .addEntry( 'js/books-genres', './assets/admin-panel/js/pages/books-genres.js' )
    .addEntry( 'js/authors', './assets/admin-panel/js/pages/authors.js' )
    .addEntry( 'js/authors-edit', './assets/admin-panel/js/pages/authors-edit.js' )
    .addEntry( 'js/products-edit', './assets/admin-panel/js/pages/products-edit.js' )
;

const config = Encore.getWebpackConfig();
config.name = 'adminPanelCusstomEntries';

module.exports = config;
