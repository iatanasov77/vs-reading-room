const Encore = require('@symfony/webpack-encore');
const path = require('path');
const AngularCompilerPlugin = require('@ngtools/webpack').AngularWebpackPlugin;

const defaultThemePath              = '../../vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default/assets';

Encore
    .setOutputPath( 'public/shared_assets/build/booksaw-book-store/' )
    .setPublicPath( '/build/booksaw-book-store/' )
  
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    
    .enableSassLoader(function(sassOptions) {}, {
        resolveUrlLoader: true
    })
    
    .addAliases({
        '@': path.resolve( __dirname, defaultThemePath ),
        '@@': path.resolve( __dirname, '../../vendor/vankosoft/payment-bundle/lib/Resources/assets' )
    })
    
    /**
     * Configure Angular Compiler and Loader
     */
    .enableTypeScriptLoader()
    .addPlugin(new AngularCompilerPlugin({
        "tsConfigPath": './themes/BooksawBookStore/assets/js/PdfViewer/tsconfig.app.json',
        "entryModule": './themes/BooksawBookStore/assets/js/PdfViewer/main.ts',
    }))
    
    /* Embed Angular Component Templates. */
    .addLoader({
        test: /\.(html)$/,
        use: 'raw-loader',
    })
    
    .autoProvidejQuery()
    .configureFilenames({
        js: '[name].js?[contenthash]',
        css: '[name].css?[contenthash]',
        assets: '[name].[ext]?[hash:8]'
    })
    
    /**
     * Copy Files
     */
    .copyFiles({
        from: path.resolve( __dirname, defaultThemePath ) + '/images',
        to: 'images/[path][name].[ext]',
    })
    
    .copyFiles({
       from: './node_modules/ng2-pdfjs-viewer/pdfjs',
       to: 'assets/pdfjs/[path][name].[ext]',
    })
    
    .copyFiles({
        from: './themes/BooksawBookStore/assets/vendor/booksaw-book-store-html-template/images',
        to: 'images/[path][name].[ext]',
    })
    
    .copyFiles({
        from: './themes/BooksawBookStore/assets/images',
        to: 'images/[path][name].[ext]',
    })
    
    /**
     * Add Entries
     */
    .addStyleEntry( 'css/app', './themes/BooksawBookStore/assets/css/app.scss' )
    .addEntry( 'js/app', './themes/BooksawBookStore/assets/js/app.js' )
    
    .addEntry( 'js/profile', './themes/BooksawBookStore/assets/js/pages/profile.js' )
    .addEntry( 'js/catalog', './themes/BooksawBookStore/assets/js/pages/catalog.js' )
    .addEntry( 'js/show-book', './themes/BooksawBookStore/assets/js/pages/show-book.js' )
    
    //.addEntry( 'js/read-book', './themes/BooksawBookStore/assets/js/pages/read-book.js' )
    .addEntry( 'js/read-book', './themes/BooksawBookStore/assets/js/PdfViewer/index.js' )
;

Encore.configureDefinePlugin( ( options ) => {
    options.IS_PRODUCTION = JSON.stringify( Encore.isProduction() );
});

const config = Encore.getWebpackConfig();
config.name = 'applicationBooksawBookStoreTheme';

config.resolve.extensions = ['.ts', '.js'];

module.exports = config;
