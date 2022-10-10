@if($product)
<section class="product_section layout_padding">
         <div class="container">
            <div class="heading_container heading_center">
               <h2>
                  Our <span>products</span>
               </h2>
            </div>
            <div class="row">
               @foreach($product as $data)
               <div class="col-sm-6 col-md-4 col-lg-4">
                  <div class="box">
                     <div class="option_container">
                        <div class="options">
                           <a href="{{url('product_details',$data->p_id)}}" class="option1">
                          Product Details
                           </a>
                           <form action="{{url('/add_to_cart/'.$data->p_id)}}" method="post">
                              @csrf
                              <div class="row">
                                 <div class="col-md-4">
                           <input type="number" name="qty" value="1" min="1" />
                           </div>  
                           <div class="col-md-4">
                           <input type="submit" value="Add to Cart"/>
                              </div>
                           </div>
                           </form>
              
                        </div>
                     </div>
                     <div class="img-box">
                     @if($data->hasMedia('product'))
    <img src="uploads/{{$data->getMedia('product')->first()->getDiskPath()}}" >
                
    @endif
                     </div>
                     <div class="detail-box">
                        <h5>
                           {{$data->p_name}}
                        </h5>
                        <h6>
                          Rs {{$data->p_sale_price}} 
                        </h6>
                        <h6 style="text-decoration:line-through">
                          Rs {{$data->p_price}}
                        </h6>
                     </div>
                  </div>
               </div>
              
               @endforeach
               <span style="padding-top: 20px;">
               
               </span>
               @endif
               
              
               
            
              
              
            
               
              
               
            </div>
            <div class="btn-box">
               <a href="">
               View All products
               </a>
            </div>
         </div>
      </section>