$( function()
{
    $( '#cat-all_genre' ).addClass( 'active' );
    
    $( '.btnCategory' ).click( function( e ) {
        e.preventDefault();
        
        let url = $( this ).attr( 'data-url' );
        $.ajax({
            type: "GET",
            url: url,
            context: this,
            success: function( response )
            {
                $( '#ProductsPageContainer' ).html( response );
                
                $( '.btnCategory.tab.active' ).removeClass( 'active' );
                $( this ).addClass( 'active' );
            },
            error: function()
            {
                alert( "SYSTEM ERROR!!!" );
            }
        });
    });
});