var Encore = require( '@symfony/webpack-encore' );

/**
 *  AdminPanel Velzon Theme
 */
Encore.reset();
const adminPanelVelzonConfig    = require( './themes/AdminPanel_VelzonChild/webpack.config' );

/**
 *  BooksawBookStore Theme
 */
Encore.reset();
const applicationBooksawBookStoreTheme = require('./themes/BooksawBookStore/webpack.config');

//=================================================================================================


module.exports = [
    adminPanelVelzonConfig,
    applicationBooksawBookStoreTheme,
];
