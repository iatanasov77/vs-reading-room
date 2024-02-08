var Encore = require( '@symfony/webpack-encore' );

/**
 *  AdminPanel Default Theme
 */
const themePath         = './vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default';
const adminPanelConfig  = require( themePath + '/webpack.config' );

//=================================================================================================

/**
 *  BooksawBookStore Theme
 */
Encore.reset();
const applicationBooksawBookStoreTheme = require('./themes/BooksawBookStore/webpack.config');

//=================================================================================================


module.exports = [
    adminPanelConfig,
    applicationBooksawBookStoreTheme,
];
