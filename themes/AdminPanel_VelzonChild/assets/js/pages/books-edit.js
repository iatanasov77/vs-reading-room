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

$( function()
{
    $( '.attributesContainer' ).duplicateFields({
        btnRemoveSelector: ".btnRemoveField",
        btnAddSelector:    ".btnAddField"
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
    
    /*
    var taxonValues = $( '#categoryTaxonIds' ).attr( 'data-values' ).split( ',' );
    $( '#book_form_category_taxon' ).combotree( 'setValues', taxonValues );
    */
    
    $( '#FormContainer' ).on( 'change', '#book_form_locale', function( e ) {
        var projectId  = $( '#FormContainer' ).attr( 'data-itemId' );
        var locale  = $( this ).val();
        
        // RETURN FOR NOW
        alert( locale );
        return;
        
        if ( projectId ) {
            $.ajax({
                type: 'GET',
                url: VsPath( 'vsorg_projects_form_in_locale', { 'itemId': projectId, 'locale': locale } ),
                success: function ( data ) {
                    $( '#FormContainer' ).html( data );
                    
                    $( '.attributesContainer' ).duplicateFields({
                        btnRemoveSelector: ".btnRemoveField",
                        btnAddSelector:    ".btnAddField"
                    });
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    alert( 'FATAL ERROR!!!' );
                }
            });
        }
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
});
