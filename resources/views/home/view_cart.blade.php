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
     

  

         <section class="vh-100">
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col">
        <p><span class="h2">Shopping Cart </span><span class="h4">( item in your cart)</span></p>

        <div class="card mb-4">
          <div class="card-body p-4">
          @php 
    $i=0;
    $total_prize=0;
    $total_discount=0;
    $total_amount_to_pay=0;
    @endphp
    @foreach($cart as $cart)
     @php 
     $i++;
     $total_prize=$total_prize+$cart->product->p_sale_price;
     $discount=$cart->product->p_price - $cart->product->p_sale_price;
     $total_discount=$total_discount+$discount;
    $amount =$cart->product->p_sale_price;
    $total_amount_to_pay = $total_amount_to_pay+$amount;
     @endphp
            <div class="row align-items-center">
              <div class="col-md-2">
                <img src="uploads/{{$cart->product->getMedia('product')->first()->getDiskPath()}}"
                  class="img-fluid" alt="Generic placeholder image">
                  <input type="hidden" name="oim_image" value="{{$cart->product->getMedia('product')->first()->getDiskPath()}}"/>
              </div>
             

              <div class="col-md-2 d-flex justify-content-center">
                <div>
                  <p class="small text-muted mb-4 pb-2">Name</p>
                  <p class="lead fw-normal mb-0">{{$cart->product->p_name}}</p>
                </div>
              </div>
              
              <div class="col-md-2 d-flex justify-content-center">
                <div>
                  <p class="small text-muted mb-4 pb-2">Quantity</p>
                  <p class="lead fw-normal mb-0">{{$cart->taken_qty}}</p>
                </div>
              </div>
              <div class="col-md-2 d-flex justify-content-center">
                <div>
                  <p class="small text-muted mb-4 pb-2">Price</p>
                  <p class="lead fw-normal mb-0">{{$cart->product->p_price}}</p>
                </div>
              </div>
              <div class="col-md-2 d-flex justify-content-center">
                <div>
                  <p class="small text-muted mb-4 pb-2">Discount</p>
                  <p class="lead fw-normal mb-0">{{$cart->product->p_price-$cart->product->p_sale_price}}</p>
                </div>
              </div>
              <div class="col-md-2 d-flex justify-content-center">
                <div>
                  <p class="small text-muted mb-4 pb-2">Total</p>
                  <p class="lead fw-normal mb-0">{{$cart->product->p_sale_price}}</p>
                </div>
              </div>
            </div>
@endforeach
          </div>
        </div>

        <div class="card mb-5">
          <div class="card-body p-4">

            <div class="float-end">
              <p class="mb-0 me-5 d-flex align-items-center">
                <span class="small text-muted me-2">Order total:</span> <span
                  class="lead fw-normal">{{$total_prize}}</span>
              </p>
            
            </div>

          </div>
        </div>

        <div class="d-flex justify-content-end">
          <button type="button" class="btn btn-light btn-lg me-2">Continue shopping</button>
          <a href="{{route('checkout')}}" class="btn btn-primary btn-lg">Place Order</a>
        </div>

      </div>
    </div>
  </div>
</section>

      </div>
    
      
 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment Mode</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a href="{{url('/cash_order')}}" class="btn btn-primary">Cash on Delivery</a>
        <a href="" class="btn btn-primary">Pay Using Card</a>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        
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
   </body>
</html>