var Encore = require( '@symfony/webpack-encore' );

/**
 *  AdminPanel Default Theme
 */
const themePath         = './vendor/vankosoft/application/src/Vankosoft/ApplicationBundle/Resources/themes/default';
const adminPanelConfig  = require( themePath + '/webpack.config' );

//=================================================================================================

/**
 *  AdminPanel Cusstom Entries
 */
Encore.reset();
const adminPanelCusstomEntriesConfig = require('./assets/admin-panel/webpack.config');

//=================================================================================================

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
    adminPanelConfig,
    adminPanelCusstomEntriesConfig,
    adminPanelVelzonConfig,
    applicationBooksawBookStoreTheme,
];
