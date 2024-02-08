const $ = require( 'jquery' );
global.$ = $;
window.$ = $;

require( 'bootstrap' );

require( './includes/vs_cookieconsent.js' );

import AOS from 'aos'
global.AOS = AOS;
window.AOS = AOS;

require( '../vendor/booksaw-book-store-html-template/js/plugins.js' );
require( '../vendor/booksaw-book-store-html-template/js/script.js' );
