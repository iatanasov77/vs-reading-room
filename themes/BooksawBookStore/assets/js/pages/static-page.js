require( '../../css/static-page.css' );

$( function ()
{
    $( ':input[required]' ).each( function( i, requiredField )
    {
        var placeholder = $( requiredField ).attr( 'placeholder' );
        if ( placeholder ) {
            $( requiredField ).attr( 'placeholder', placeholder + ' *' );
        }
    });
});