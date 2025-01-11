require( '@/js/includes/widgets/ajax-widget' );

var routes  = require( '../../../../../public/shared_assets/js/fos_js_routes_application.json' );
import { VsPath } from '@/js/includes/fos_js_routes.js';
  
const afterLoad = function() {
    $( '#bookSuggestionsContainer' ).children( 'div.product-item' ).css( "text-align", "center" );
    $( '#bookSuggestionsContainer' ).children( 'div.product-item' ).css( "width", "300px" );
    $( '#bookSuggestionsContainer' ).children( 'div.product-item' ).css( "float", "left" );
    $( '#bookSuggestionsContainer' ).parent().css( "display", "grid" );
    $( '#bookSuggestionsContainer' ).parent().css( "justify-content", "center" );
}

$( function()
{
    var tooltipTriggerList = [].slice.call( document.querySelectorAll( '[data-bs-toggle="tooltip"]' ) );
    var tooltipList = tooltipTriggerList.map( function ( tooltipTriggerEl ) {
        return new bootstrap.Tooltip( tooltipTriggerEl )
    });

    var bookSuggestionsUrl  = VsPath( 'app_reading_room_book_suggestions', { productSlug: productSlug }, routes );
    $( '#bookSuggestionsContainer' ).widget({ callback: bookSuggestionsUrl, afterLoad: afterLoad });
});