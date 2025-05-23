require( 'jquery-easyui/css/easyui.css' );
require( 'jquery-easyui/js/jquery.easyui.min.js' );
// Need copy of: jquery-easyui/images/*

require ( 'jquery-duplicate-fields/jquery.duplicateFields.js' );
require( '@/js/includes/bootstrap-5/file-input.js' );

require( 'jquery-easyui-extensions/EasyuiCombobox.css' );
import { EasyuiCombobox } from 'jquery-easyui-extensions/EasyuiCombobox.js';
import { VsRemoveDuplicates } from '@/js/includes/vs_remove_duplicates.js';

// bin/console fos:js-routing:dump --format=json --target=public/shared_assets/js/fos_js_routes_admin.json
import { VsPath } from '@/js/includes/fos_js_routes.js';

import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';

import DragSort from '@yaireo/dragsort';
import '@yaireo/dragsort/dist/dragsort.css';
    
var tagsInput;
var tagify;
var dragsort;

// must update Tagify's value according to the re-ordered nodes in the DOM
function onDragEnd( elm )
{
    tagify.updateValueByDOMTags();
}

function initForm()
{
    $( '.picturesContainer' ).duplicateFields({
        btnRemoveSelector: ".btnRemoveField",
        btnAddSelector:    ".btnAddField",
        onCreate: function( newElement ) {
            let fileInputId = newElement.find( '.fieldPhoto' ).attr( 'id' );
            newElement.find( '.input-group-text' ).attr( 'for', fileInputId );
        }
    });
    
    $( '.picturesContainer' ).on( 'change', '.fieldPhoto', function() {
        var filename = $( this ).val().split('\\').pop();
        $( this ).next( '.input-group-text' ).text( filename );
    });
    
    $( '.filesContainer' ).duplicateFields({
        btnRemoveSelector: ".btnRemoveField",
        btnAddSelector:    ".btnAddField",
        onCreate: function( newElement ) {
            let fileInputId = newElement.find( '.fieldFile' ).attr( 'id' );
            newElement.find( '.input-group-text' ).attr( 'for', fileInputId );
        }
    });
    
    $( '.filesContainer' ).on( 'change', '.fieldFile', function() {
        var filename = $( this ).val().split('\\').pop();
        $( this ).next( '.input-group-text' ).text( filename );
    });
    
    let associationsSelector    = ".product-associations";
    EasyuiCombobox( $( associationsSelector ), {
        required: false,
        multiple: true,
        checkboxId: "product_associations",
        values: null,
        getValuesFrom: 'select-box',
        debug: false
    });
    
    let categorySelector    = "#book_form_categories";
    let selectedCategories  = JSON.parse( $( '#book_form_productCategories').val() );
    EasyuiCombobox( $( categorySelector ), {
        required: true,
        multiple: true,
        checkboxId: "product_category",
        values: selectedCategories,
        debug: false
    });
    
     var tagsInputWhitelist  = $( '#book_form_tagsInputWhitelist' ).val().split( ',' );
    //console.log( tagsInputWhitelist );
    
    tagsInput   = $( '#book_form_tags' )[0];
    tagify      = new Tagify( tagsInput, {
        whitelist : tagsInputWhitelist,
        dropdown : {
            classname     : "color-blue",
            enabled       : 0,              // show the dropdown immediately on focus
            maxItems      : 5,
            position      : "text",         // place the dropdown near the typed text
            closeOnSelect : false,          // keep the dropdown open after selecting a suggestion
            highlightFirst: true
        }
    });
    
    // bind "DragSort" to Tagify's main element and tell
    // it that all the items with the below "selector" are "draggable"
    dragsort    = new DragSort( tagify.DOM.scope, {
        selector: '.'+tagify.settings.classNames.tag,
        callbacks: {
            dragEnd: onDragEnd
        }
    }); 
    
    VsRemoveDuplicates();
    
    let selectedGenres  = JSON.parse( $( '#book_form_bookGenres').val() );
    EasyuiCombobox( $( '#book_form_genres' ), {
        required: false,
        multiple: true,
        checkboxId: "genres",
        values: selectedGenres,
        debug: true
    });
    
    let selectedAuthors  = JSON.parse( $( '#book_form_bookAuthors').val() );
    EasyuiCombobox( $( '#book_form_authors' ), {
        required: false,
        multiple: true,
        checkboxId: "books",
        values: selectedAuthors,
        debug: true
    });
}

$( function()
{
    $( '#TocDocumentField' ).hide();
    initForm();
    
    /*
    var taxonValues = $( '#categoryTaxonIds' ).attr( 'data-values' ).split( ',' );
    $( '#book_form_category_taxon' ).combotree( 'setValues', taxonValues );
    */
    
    $( '#FormContainer' ).on( 'change', '#book_form_locale', function( e ) {
        // CkEditor 5 Is Not Initialized on Ajax Reload;
        return;
    
        var bookId  = $( '#FormContainer' ).attr( 'data-itemId' );
        var locale  = $( this ).val();
        
        if ( bookId ) {
            $.ajax({
                type: 'GET',
                url: VsPath( 'vs_reading_room_books_form_in_locale', { 'itemId': bookId, 'locale': locale } ),
                success: function ( data ) {
                    $( '#FormContainer' ).html( data );
                    
                    initForm();
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert( 'FATAL ERROR!!!' );
                }
            });
        }
    });
    
    $( '#FormContainer' ).on( 'change', '#book_form_bookType', function( e ) {
        if ( $( this ).val() == 'vankosoft_document' ) {
            $( '#TocDocumentField' ).show();
        } else {
            $( '#TocDocumentField' ).hide();
        }
    });
});
