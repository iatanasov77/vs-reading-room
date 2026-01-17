require ( '@@/js/includes/BootstrapDropdown.js' );
require ( '@vankosoft/jquery-duplicate-fields/jquery.duplicateFields.js' );

require( 'jquery-easyui/css/easyui.css' );
require( 'jquery-easyui/js/jquery.easyui.min.js' );
require( '@@/js/includes/bootstrap-5/file-input.js' );

import { VsPath } from '@@/js/includes/fos_js_routes.js';

import { VsRemoveDuplicates } from '@@/js/includes/vs_remove_duplicates.js';
import { EasyuiCombobox } from '@vankosoft/jquery-easyui-extensions/EasyuiCombobox.js';

$( function ()
{
    $( '#PhotosContainer' ).duplicateFields({
        btnRemoveSelector: ".btnRemoveField",
        btnAddSelector:    ".btnAddField",
        onCreate: function( newElement ) {
            let fileInputId = newElement.find( '.fieldPhoto' ).attr( 'id' );
            newElement.find( '.input-group-text' ).attr( 'for', fileInputId );
        }
    });
    
    $( '#PhotosContainer' ).on( 'change', '.fieldPhoto', function() {
        var filename = $( this ).val().split('\\').pop();
        $( this ).next( '.input-group-text' ).text( filename );
    });
    
    let selectedBooks  = JSON.parse( $( '#book_author_form_authorBooks').val() );
    EasyuiCombobox( $( '#book_author_form_books' ), {
        required: false,
        multiple: true,
        checkboxId: "books",
        values: selectedBooks,
        debug: true
    });
    VsRemoveDuplicates();
    
    let selectedGenres  = JSON.parse( $( '#book_author_form_authorGenres').val() );
    EasyuiCombobox( $( '#book_author_form_genres' ), {
        required: false,
        multiple: true,
        checkboxId: "genres",
        values: selectedGenres,
        debug: true
    });
    
    $( '.persistedPhoto' ).removeAttr( 'required' );
    
    // bin/console fos:js-routing:dump --format=json --target=public/shared_assets/js/fos_js_routes_admin.json
    $( '#FormContainer' ).on( 'change', '#book_author_form_locale', function( e ) {
        var authorId  = $( '#FormContainer' ).attr( 'data-itemId' );
        var locale  = $( this ).val();
        //alert( locale );
        
        if ( actorId ) {
            $.ajax({
                type: 'GET',
                url: VsPath( 'vs_reading_room_authors_form_in_locale', { 'itemId': authorId, 'locale': locale } ),
                success: function ( data ) {
                    $( '#FormContainer' ).html( data );
                }, 
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert( 'FATAL ERROR!!!' );
                }
            });
        }
    });
    
});