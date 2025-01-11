import { VsPath } from '@/js/includes/fos_js_routes.js';
require( '@/js/includes/clone_preview.js' );
require( '@/js/includes/resource-delete.js' );
require( '../../css/custom.css' );

$( function()
{
	$( "#form_filterByCategory" ).on( 'change', function() {
        let filterCategory  = $( this ).val();
        let url             = VsPath( 'vs_catalog_product_index' );
        if ( filterCategory ) {
            url = VsPath( 'vs_catalog_product_index_filtered', { 'filterCategory': filterCategory } );
        }
        
        document.location   = url;
    });
    
    $( "#form_filterByGenre" ).on( 'change', function() {
        let filterGenre  = $( this ).val();
        let url             = VsPath( 'vs_catalog_product_index' );
        if ( filterGenre ) {
            url = VsPath( 'vs_catalog_product_index_filter_by_genre', { 'filterGenre': filterGenre } );
        }
        
        document.location   = url;
    });
    
    $( "#form_filterByAuthor" ).on( 'change', function() {
        let filterAuthor  = $( this ).val();
        let url             = VsPath( 'vs_catalog_product_index' );
        if ( filterAuthor ) {
            url = VsPath( 'vs_catalog_product_index_filter_by_author', { 'filterAuthor': filterAuthor } );
        }
        
        document.location   = url;
    });
});
