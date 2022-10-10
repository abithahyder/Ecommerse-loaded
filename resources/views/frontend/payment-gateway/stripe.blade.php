<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
    <style type="text/css">
        .panel-heading {
            width: 100%
        }
        .panel-title {
        display: inline;
        font-weight: bold;
        }
        .display-table {
            display: table;
        }
        .display-tr {
            display: table-row;
        }
        .display-td {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
</head>
<body>
  
<div class="container">
  
    <h1>Payment</h1>
  
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default credit-card-box">
                <div class="panel-heading display-table" >
                    <div class="row display-tr" >
                        <h3 class="panel-title display-td" >Payment Details</h3>
                        {{-- <div class="display-td" >                            
                            <img class="img-responsive pull-right" src="http://i76.imgup.net/accepted_c22e0.png">
                        </div> --}}
                    </div>                    
                </div>
                <div class="panel-body">
  
                    @if (Session::has('success'))
                        <div class="alert alert-success text-center">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    <?php
                        $stripe_key = ! empty( $paymentSettings ) ? $paymentSettings['stripe_key'] : env( 'STRIPE_KEY' );
                    ?>
                    <form action="{{ route('stripe.post') }}" method="post" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{ $stripe_key }}" id="payment-form">
                        @csrf
  
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group required'>
                                <label class='control-label'>Name on Card</label> 
                                <input type='text' name="cardholder_name" value="" class='form-control' size='4'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-xs-12 form-group card required'>
                                <label class='control-label'>Card Number</label> 
                                <input type='text' name="card_number" value="" autocomplete='off' class='form-control card-number' size='20'>
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label> 
                                <input type='text' name="cvv" value="" autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4'>
                            </div>
                            <?php $months = [ 'Jan', 'Feb', 'March', 'April', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ]; ?>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Month</label> 
                                <select name="month" id="month" class="form-control card-expiry-month">
                                    <?php $i = 1; ?>
                                    <option value="">Select Month</option>
                                    @foreach ( $months as $month )
                                        <option value="{{ $i }}">{{ $month }}</option>
                                        <?php $i++; ?>
                                    @endforeach
                                </select>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Year</label>
                                <select name="year" id="year" class="form-control card-expiry-year">
                                    <?php $year = date( 'Y', time() ); $limit_year = $year + 100; ?>
                                    <option value="">Select Year</option>
                                    @for($year = $year; $year <= $limit_year; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select> 
                                {{-- <input type='text' class='form-control card-expiry-year' placeholder='YYYY' size='4' maxlength="4""> --}}
                            </div>
                        </div>
  
                        <div class='form-row row'>
                            <div class='col-md-12 error form-group card-error-message'>
                                {{-- <div class='alert-danger alert'>Please correct the errors and try again.</div> --}}
                            </div>
                        </div>
  
                        <div class="row">
                            <div class="col-xs-12">
                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now</button>
                            </div>
                        </div>
                          
                    </form>
                </div>
            </div>        
        </div>
    </div>
      
</div>
  
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset( 'js/frontend/stripe-custom.js' )}}"></script>
</body>
  
  

</html>