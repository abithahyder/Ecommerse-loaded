$(document).ready( function() {

    setting_box_hide();

    var active_gateway = $( ".active_gateway" ).val();
    if( active_gateway ) {
        $( "."+active_gateway+"-settings" ).show();
        $( ".payment-select" ).removeAttr( "data-ktwizard-state" );
        $( 'a[data-id = '+active_gateway+'-settings ]' ).attr( "data-ktwizard-state", "current" );
    }
    
    $( ".payment-select" ).on( "click", function () {
        $( ".payment-select" ).removeAttr( "data-ktwizard-state" );
        $( this ).attr( "data-ktwizard-state", "current" );
        setting_box_hide();
        var selected_payment = $( this ).attr( "data-id" );
        $( "."+selected_payment ).show();
    });

    function setting_box_hide() {
        $( ".stripe-settings" ).hide();
        $( ".paypal-settings" ).hide();
        $( ".razorpay-settings" ).hide();
        $( ".paytm-settings" ).hide();
    }

});