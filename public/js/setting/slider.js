
$( document ).on( 'click', '.add_admin',  function(){
    $( '.add_new_admin_form' ).stop().slideToggle();
} );

var table;
$(document).ready(function() {
    
    table = $('#admin_list_table').DataTable();
});


$('#admin_add_form').validate({
    focusInvalid: true,
    rules: {
        hs_order: 'required',
    },
    messages: {
      
    },
    highlight: function(element) {
        $(element).addClass("is-invalid");
    },
    unhighlight: function(element) {
        $(element).removeClass("is-invalid");
    },
    submitHandler: function(form) {
      form.submit();
    }
});
