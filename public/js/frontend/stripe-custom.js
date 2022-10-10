$( document ).ready( function() {

    $(".card-error-message").hide();

    $( '#payment-form' ).validate( {

        ignore:[],

        rules : {
            cardholder_name : {
                required : true
            },
            card_number : {
                required  : true,
                minlength : 16,
                maxlength : 16
            },
            cvv : {
                required  : true,
                minlength : 3,
                maxlength : 3
            },
            year: {
                CCExp: {
                    formMonth: "#month",
                    formYear: "#year"
                }
            },
            terms : {
                required : true
            },
        },

        messages : {
            card_number : {
                required  : "Please enter a Card Number",
                minlength : "Your Card Number must be at list 16 digit long",
                maxlength : "Your Card Number should not be more then 16 digit"
            },
            cvv : {
                required  : "Please enter a CVV",
                minlength : "Your CVV Number must be at list 3 digit long",
                maxlength : "Your CVV Number should not be more then 3 digit"
            },

            cardholder_name : "Card Holder Name Required",
            terms           : "Please accept our terms and conditions",
        },

        errorElement : 'span',

        errorPlacement: function ( error, element ) {
            error.addClass( 'invalid-feedback' );
            element.closest( '.form-group' ).append( error );
        },

        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( 'is-invalid' );
        },

        unhighlight: function ( element, errorClass, validClass ) {
            $( element ).removeClass( 'is-invalid' );
        }
    });

    $.validator.addMethod("CCExp", function(value, element, params) {
        var minMonth = new Date().getMonth() + 1;
        var minYear = new Date().getFullYear();

        var formMonth = $('#month').val();
        var formYear = $('#year').val();

        var month = parseInt(formMonth);
        var year = parseInt(formYear);


        if ((formYear > minYear) || ((formYear === minYear) && (formMonth >= minMonth))) {
            return true;
        } else {
            return false;
        }
    }, "Your Card Expiration date is invalid.");


    $(function() {
    
        var $form = $(".require-validation");
    
        $('form.require-validation').bind('submit', function(e) {
                
            var $form     = $(".require-validation"),
            inputSelector = ['input[type=email]', 'input[type=password]',
                            'input[type=text]', 'input[type=file]',
                            'textarea'].join(', '),
            $inputs       = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid         = true;
            
            $('.has-error').removeClass('has-error');
            $inputs.each(function(i, el) {
            var $input = $(el);
            if ($input.val() === '') {
                $input.parent().addClass('has-error');
                $errorMessage.removeClass('hide');
                e.preventDefault();
            }
            });

            if (!$form.data('cc-on-file')) {
            e.preventDefault();
            Stripe.setPublishableKey($form.data('stripe-publishable-key'));
            Stripe.createToken({
                number: $('.card-number').val(),
                cvc: $('.card-cvc').val(),
                exp_month: $('.card-expiry-month').val(),
                exp_year: $('.card-expiry-year').val()
            }, stripeResponseHandler);
            }
        
        });
    
        function stripeResponseHandler(status, response) {
            
            if ( response.error ) {
                
                $(".process-btn").attr("disabled", false);
                $(".process-btn").text('Proceed Payment');
                $('.card-error-message').hide();
                $('.card-error-message').show();
                alert(response.error.message);
                $(document).find('.card-error-message').html("<div class='alert alert-danger alert-dismissible'>"+response.error.message+"<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button></div>");
            
            } else {
                /* token contains id, last4, and card type */
                var token = response['id'];

                $form.find('input[type=text]').empty();
                $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
                $form.get(0).submit();
            }
        }
    
    });
});