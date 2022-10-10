<!DOCTYPE html>
<html>
   <head>
      <!-- Basic -->
      <base href="/public" />
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
      
     @if($product)
      <div class="col-md-12" style="margin: auto; width:50%; padding:30px;">
                 
                     
                     <div class="img-box">
                        <img src="product/{{$product->product_image}}" alt="">
                     </div>
                     <div class="detail-box">
                        <h5>
                           {{$product->title}}
                        </h5>
                        <h6>
                          Rs {{ $product->seller_prize - $product->discount }} 
                        </h6>
                        <h6 style="text-decoration:line-through">
                          Rs {{$product->seller_prize}}
                        </h6>
                        <h6>Product Category:{{$product->productcategory->Product_category_name}}</h6>
                        <h6>Description:<h6><p> {{$product->description}}</p><br/>
                        <h6>Prize: {{$product->seller_prize}}</h6>
                        <h6>Amount After Discount:{{$product->seller_prize - $product->discount }}</h6>
                        <h6>Availability:{{$product->quantity}}</h6>
                        <h6>Discount:{{$product->discount}}</h6>
                        <a href=""><button class="btn btn-primary">Add to Cart</button></a>
                     </div>
      </div>
               </div>

    @endif
     
      <!-- end client section -->
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