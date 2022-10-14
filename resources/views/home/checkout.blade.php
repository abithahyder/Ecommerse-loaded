<!DOCTYPE html>
<html>
   <head>
      <!-- Basic -->
      <meta charset="utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <!-- Mobile Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
      <!-- Site Metas -->
      <meta name="keywords" content="" />
      <meta name="description" content="" />
      <meta name="author" content="" />
      <link rel="shortcut icon" href="images/favicon.png" type="">
      <title>Famms - Fashion HTML Template</title>
      <!-- bootstrap core css -->
      <link rel="stylesheet" type="text/css" href="home/css/bootstrap.css" />
      <!-- font awesome style -->
      <link href="home/css/font-awesome.min.css" rel="stylesheet" />
      <!-- Custom styles for this template -->
      <link href="home/css/style.css" rel="stylesheet" />
      <!-- responsive style -->
      <link href="home/css/responsive.css" rel="stylesheet" />

   </head>
   <body>
      <div class="hero_area">
         <!-- header section strats -->
         @include('home.header')
         <!-- end header section -->
    
         @if(session()->has('message'))
         <div class="alert alert-success">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
          {{session()->get('message')}}
         </div>
         @endif
     


<div class="container">
  

  <div class="row">
    <div class="col-md-4 order-md-2 mb-4">
      <h4 class="d-flex justify-content-between align-items-center mb-3">
        <span class="text-muted">Your cart</span>
        <span class="badge badge-secondary badge-pill">{{$count}}</span>
      </h4>
      <ul class="list-group mb-3">
        @php 
        $total_prize=0;
        @endphp
        @foreach($cart as $cart)
       @php $total_prize=$total_prize+$cart->product->p_sale_price; @endphp
      <li class="list-group-item d-flex justify-content-between lh-condensed">
          <div>
            <h6 class="my-0">{{$cart->product->p_name}}</h6>
            <small class="text-muted">{{$cart->product->p_short_desc}}</small>
          </div>
          <span class="text-muted">{{$cart->product->p_sale_price}}</span>
           
        </li>
       @endforeach
       
        <li class="list-group-item d-flex justify-content-between bg-light">
          <div class="text-success">
            <h6 class="my-0">Promo code</h6>
            <small>EXAMPLECODE</small>
          </div>
          <span class="text-success">-$5</span>
        </li>
        <li class="list-group-item d-flex justify-content-between">
          <span>Total </span>
          <strong>{{$total_prize}}</strong>
        </li>
      </ul>

      <form class="card p-2" action="{{route('coupon.apply')}}" method="post">
      @csrf
        <div class="input-group">
          <input type="text" class="form-control" name="coupon_code" placeholder="Promo code">
          <div class="input-group-append">
            <button type="submit" name="redeemcode" class="btn btn-secondary">Redeem</button>
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-8 order-md-1">
      <h4 class="mb-3">Billing address</h4>
      <form  action="{{route('checkout.save')}}" method="POST">
      @csrf
        <div class="row">
          <!-- <div class="col-md-6 mb-3">
            <label for="firstName">Name</label>
            <input type="text" class="form-control" name="ciname" id="firstName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div> -->
          
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="firstName">Contact Number 1</label>
            <input type="text" class="form-control" name="cinum1" id="firstName" placeholder="" value="" required>
            
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="lastName">Contact Number 2</label>
            <input type="text" class="form-control"  name="cinum2" id="lastName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
        </div>
       

        

        <div class="mb-3">
          <label for="address">Address</label>
          <input type="text" class="form-control" name="ciaddrss1" id="address" placeholder="1234 Main St" required>
          <div class="invalid-feedback">
            Please enter your shipping address.
          </div>
        </div>

        <div class="mb-3">
          <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
          <input type="text" class="form-control" name="ciaddrss2" id="address2" placeholder="Apartment or suite">
        </div>

        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="country">Country</label>
            <select class="custom-select d-block w-100" name="cicountry" id="country" required>
              <option value="">Choose...</option>
               <option value="india">India</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid country.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="state">State</label>
            <select class="custom-select d-block w-100" name="cistate" id="state" required>
              <option value="">Choose...</option>
              <option>Kerala</option>
              <option>Gujarat</option>
              <option>Tamil Nadu</option>
            </select>
            <div class="invalid-feedback">
              Please provide a valid state.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="zip">city</label>
            <input type="text" class="form-control" name="cicity" id="cicity" placeholder="" required>
            <div class="invalid-feedback">
              Zip code required.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="zip">Zip</label>
            <input type="text" class="form-control" name="cipost" id="cipost" placeholder="" required>
            <div class="invalid-feedback">
              Zip code required.
            </div>
          </div>
        </div>
        <h4 class="mb-3">Shipping address</h4>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="firstName">Name</label>
            <input type="text" class="form-control" name="sname" id="firstName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
          
        </div>
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="firstName">Contact Number 1</label>
            <input type="text" class="form-control"  name="snum1" id="firstName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid first name is required.
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="lastName">Contact Number 2 (optional)</label>
            <input type="text" class="form-control" name="snum2" id="lastName" placeholder="" value="" required>
            <div class="invalid-feedback">
              Valid last name is required.
            </div>
          </div>
        </div>
        <div class="mb-3">
          <label for="address">Address</label>
          <input type="text" class="form-control"  name="saddrss1" id="address" placeholder="1234 Main St" required>
          <div class="invalid-feedback">
            Please enter your shipping address.
          </div>
        </div>

        <div class="mb-3">
          <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
          <input type="text" class="form-control" name="saddrss2" id="address2" placeholder="Apartment or suite">
        </div>
        <div class="row">
          <div class="col-md-5 mb-3">
            <label for="country">Country</label>
            <select class="custom-select d-block w-100" name="scountry" id="scountry" required>
              <option value="">Choose...</option>
              <option>India</option>
            </select>
            <div class="invalid-feedback">
              Please select a valid country.
            </div>
          </div>
          <div class="col-md-4 mb-3">
            <label for="state">State</label>
            <select class="custom-select d-block w-100"  name="sstate" id="sstate" required>
              <option value="">Choose...</option>
              <option>Kerala</option>
              <option>Gujarat</option>
              <option>Tamil Nadu</option>
            </select>
            <div class="invalid-feedback">
              Please provide a valid state.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="zip">city</label>
            <input type="text" class="form-control" name="scity" id="zip" placeholder="" required>
            <div class="invalid-feedback">
              Zip code required.
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="zip">Zip</label>
            <input type="text" class="form-control" name="spost" id="zip" placeholder="" required>
            <div class="invalid-feedback">
              Zip code required.
            </div>
          </div>
        </div>

        <hr class="mb-4">
        <!-- <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="same-address">
          <label class="custom-control-label" for="same-address">Shipping address is the same as my billing address</label>
        </div>
        <div class="custom-control custom-checkbox">
          <input type="checkbox" class="custom-control-input" id="save-info">
          <label class="custom-control-label" for="save-info">Save this information for next time</label>
        </div> -->
        <hr class="mb-4">

        <h4 class="mb-3">Payment</h4>

        <div class="d-block my-3">
          <div class="custom-control custom-radio">
            <input id="credit" name="paymentMethod" type="radio" value="Credit Card" class="custom-control-input" checked required>
            <label class="custom-control-label" for="credit">Credit card</label>
          </div>
          <div class="custom-control custom-radio">
            <input id="debit" name="paymentMethod" type="radio"  value="Debit Card" class="custom-control-input" required>
            <label class="custom-control-label" for="debit">Debit card</label>
          </div>
          <div class="custom-control custom-radio">
            <input id="paypal" name="paymentMethod" type="radio" value="Paypal" class="custom-control-input" required>
            <label class="custom-control-label" for="paypal">PayPal</label>
          </div>
          <div class="custom-control custom-radio">
            <input id="cash" name="paymentMethod" type="radio" value="CashOnDelivery"class="custom-control-input" required>
            <label class="custom-control-label" for="cash">Cash on Delivery</label>
          </div>
        </div>
        <div id="carddetail">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label for="cc-name">Name on card</label>
            <input type="text" class="form-control" id="cc-name" placeholder="" >
            <small class="text-muted">Full name as displayed on card</small>
            <div class="invalid-feedback">
              Name on card is required
            </div>
          </div>
          <div class="col-md-6 mb-3">
            <label for="cc-number">Credit card number</label>
            <input type="text" class="form-control" id="cc-number" placeholder="" >
            <div class="invalid-feedback">
              Credit card number is required
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-3 mb-3">
            <label for="cc-expiration">Expiration</label>
            <input type="text" class="form-control" id="cc-expiration" placeholder="" >
            <div class="invalid-feedback">
              Expiration date required
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <label for="cc-cvv">CVV</label>
            <input type="text" class="form-control" id="cc-cvv" placeholder="" >
            <div class="invalid-feedback">
              Security code required
            </div>
          </div>
        </div>
        </div>
        <hr class="mb-4">
        <input  class="btn btn-primary btn-lg btn-block" type="submit" value="checkout"/>
        <!-- <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button> -->
      </form>
    </div>
  </div>

</div>
      </div>
 
      <!-- footer start -->
      @include('home.footer')
      <!-- footer end -->
      <div class="cpy_">
         <p class="mx-auto">© 2021 All Rights Reserved By <a href="https://html.design/">Free Html Templates</a><br>
         
            Distributed By <a href="https://themewagon.com/" target="_blank">ThemeWagon</a>
         
         </p>
      </div>
      <!-- jQery -->
      <script src="home/js/jquery-3.4.1.min.js"></script>
      <!-- popper js -->
      <script src="home/js/popper.min.js"></script>
      <!-- bootstrap js -->
      <script src="home/js/bootstrap.js"></script>
      <!-- custom js -->
      <script src="home/js/custom.js"></script>
      <script src="home/js/checkout.js"></script>
   </body>
</html>