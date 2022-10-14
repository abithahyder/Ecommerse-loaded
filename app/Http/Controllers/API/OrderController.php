<?php

namespace App\Http\Controllers\API;

use App\cart;
use App\ClientAddress;
use App\Coupons;
use App\delivery_charge;
use App\Http\Controllers\Controller;
use App\Order as Order;
use App\Ordermaster;
use App\Product;
use App\skus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OrderController extends Controller
{
   public function add_order(Request $request){
    $validator = Validator::make($request->all(), [
        'delivery_charge'   => 'required',
          'discount' => 'nullable', 
          'addrss' => 'required',
          'scountry'=>'required',
          'sstate' =>'required',
          'sname' =>'required',
          'spost' =>'required',
           'coupons' =>'nullable|array', 
           'payment_mode' =>'required',
            
    ],[],[
        'delivery_charge'   => 'Pin Code',
        'discount' => 'Discount',
        'addrss' =>'Shipping Address',
        'sname' =>'Shipping Name',
        'sstate' =>'Shipping State',
        'scountry' =>'Shipping Country',
        'payment_mode' =>'payment Method',
        'spost' =>'Shipping Pincode',
         'coupons' =>'Reedem Code',
               ]);

    if ($validator->fails()) {
        return response()->json([
            'status'    => false,
            'message'   => 'The following fields are required',
            'error'     => $validator->errors(),
        ], 200);
    }
    $user=Auth::user();
     $userid=$user->c_id;

    
    
    $data =Cart::where('user_id','=',$userid)->get();
    if(empty($data)){
        return response()->json([
            'status'    => false,
            'message'   => 'Cart is empty',
                    ], 200);
    }
    else{
        $orderinvoice=DB::table('orders')->latest()->first();
        if($orderinvoice!=""){
        $invoice=$orderinvoice->invoice_number;
        $invoice++;
        $orderno=$orderinvoice->or_no;
        $orderno++;
         }
     else{
      $invoice='#101-11-0';
      $orderno='OR-ID-00000';
       }
      $total_items=0;
      $total_price=0;
        date_default_timezone_set("Asia/Calcutta");
        $date =date("Y-m-d H:i:s");
        DB::beginTransaction();
        try{
   
            foreach($data as $data){
            $total_items++;
            


            $ordermodel = new Order();
            if($data->p_vo_id!="" && $data->sku!=""){
                $gsku=skus::where('sku_id',$data->sku)->first(); 
                 $ordermodel->price=$gsku->sku_price;
                $ordermodel->name=$gsku->sku_name;
                if($data->taken_qty<=$gsku->sku_qty){
                    $ordermodel->qty=$data->taken_qty;
                    $gsku->sku_qty=$gsku->sku_qty-$data->taken_qty;
                    $total_price=$total_price+($gsku->sku_price*$data->taken_qty);
                    $ordermodel->total_price=$gsku->sku_price*$data->taken_qty;
                    $ordermodel->discount=$data->product->p_price-$gsku->sku_price;
                    $gsku->update();
                   
                }   
                else{
                    DB::rollback();
            
                    return response()->json([
                  'status'    => false,
                  'message'   => 'Product is Out of Stock Try again',
                  ], 200);
                  
                }
            }
            else{
                $oprice=$data->product->p_sale_price;
                
                $ordermodel->price=$data->product->p_sale_price;
                $ordermodel->name=$data->product->p_name;
                if($data->taken_qty<=$data->product->p_stock){
                    $ordermodel->qty=$data->taken_qty;

                $product=Product::where('p_id',$data->product->p_id)->first();
                $product->p_stock=$data->product->p_stock-$data->taken_qty;
                $product->update();
               

                }
                else{
                    DB::rollback();
                    return response()->json([
                  'status'    => false,
                  'message'   => 'Product is Outs of Stock Try again',
                  ], 200);
                }
                $total_price=$total_price+$oprice*$data->taken_qty;
       
                $ordermodel->total_price=$oprice*$data->taken_qty;  
                $ordermodel->discount=$data->product->p_price-$data->product->p_sale_price;
            }
           
            $ordermodel->invoice_number	=$invoice;
            $ordermodel->or_no =$orderno;
            $ordermodel->u_id=$data->user_id;
            $ordermodel->p_id=$data->p_id;
            $ordermodel->p_vo_id=$data->p_vo_id;
            $ordermodel->sku=$data->sku;
            $ordermodel->payment_mode=$request->payment_mode;
            $ordermodel->pay_status="pending";
            $ordermodel->delivery_status="processing";
            $ordermodel->oim_image=$data->product->getMedia('product')->first()->getDiskPath();
            $ordermodel->save();
            $cartid=$data->cart_id;
            $cart=cart::findorFail($cartid);
            $cart->delete();

          }
    //  $chargem=delivery_charge::where('dc_postcode','=',$request->spost)->first();
    
            $charge=$request->delivery_charge;
            $tdiscount=$request->discount;
    
            if($tdiscount==""){
                $grand_total = $total_price + $charge;
            }
            else{
                $grand_total = $total_price + $charge -$tdiscount;
            }
       
          $ordermas=new Ordermaster();
           $ordermas->om_u_id=$userid;
           $ordermas->om_or_id_no=$orderno;
           $ordermas->om_date=$date;
           $ordermas->om_status="pending";
           $ordermas->om_total=$total_price;
           $ordermas->total_items=$total_items;
           $ordermas->or_invoice_number=$invoice;
           $ordermas->om_addresline_1=$request->addrss;
           if($request->has('addrss2')){
            $ordermas->om_addresline_2=$request->addrss2;
           }
           if($request->has('snum1')){
            $ordermas->om_mobile=$request->snum1;
           }
           if($request->has('scity')){
            $ordermas->om_city=$request->scity; 
           }
           $ordermas->om_pincode=$request->spost;
           $ordermas->om_state=$request->sstate;     
           $ordermas->om_sname=$request->sname;
           $ordermas->om_country=$request->scountry;
           $ordermas->om_grand_total=$grand_total;
           $ordermas->payment=$request->payment_mode;
           $ordermas->delivery_charge=$charge;
           $ordermas->reedem_code=implode(',',$request->coupons);
           $ordermas->save();
        //    $rcoupons=implode(',',$request->coupons);
      
           $rcoupons=$request->coupons;
           foreach($rcoupons as $reedemcoupons){
            Coupons::where('cm_id',$reedemcoupons)->increment('cm_usage_count',1);
           }
            $model=DB::table('client_addresses') ->where('ca_client_id', '=', $userid)->first();
             if($model!=""){
                $uuid=$model->ca_id;
            $clientmodel = ClientAddress::findorFail($uuid);
           }
           else{
            $clientmodel = new ClientAddress();
           
           }
          
           $clientmodel->ca_client_id=$userid;
           $clientmodel->ca_address_line_1=$request->ciaddrss1;
           $clientmodel->ca_address_line_2=$request->ciaddrss2;      
            $clientmodel->ca_mobile=$request->cinum1;
           $clientmodel->ca_alter_num=$request->cinum2;
             $clientmodel->ca_city=$request->cicity;
             $clientmodel->ca_state=$request->cistate;
           $clientmodel->ca_country=$request->cicountry;
           $clientmodel->ca_pincode =$request->cipost;
           $clientmodel->save();
        }catch (\Throwable $th) {
            DB::rollback();
            
            return response()->json([
                'status'    => false,
                'message'   => 'something went wrong',
                'error'     => $th->getMessage(),
            ], 200);
        }
        DB::commit();
                  
      return response()->json([
        'status'    => true,
        'message'   => 'We have Received your order.we will connect with you soon...',
        'error'     => '',
        ], 200);
    }
}
   


public function getdeleiverycharge(Request $request){
    $validator = Validator::make($request->all(), [
        'zip'   => 'required',
              
    ],[],[
        'zip'   => 'Post Code',
               ]);
    if ($validator->fails()) {
        return response()->json([
            'status'    => false,
            'message'   => 'The following fields are required',
            'error'     => $validator->errors(),
        ], 200);
    }
    try{
        $data= delivery_charge::where('dc_postcode',$request->zip)->first();
        if(empty($data)){
            $charge=0;
        }
        else {
            $charge=$data->dc_price;
        }
        return response()->json([
            'status'  => true,
            'message' => 'success',
            'delivery_charge' => $charge
        ], 200);

    }catch(\Throwable $th){
        return response()->json([
            'status'  => false,
            'message' => 'Some error occured Try again',
            'erorr' => $th->getMessage()
        ], 200);
    }

}

public function checkcoupon(Request $request){
    $validator = Validator::make($request->all(), [
        'coupon_code'   => 'required',
              
     ],[],[
        'coupon_code'   => 'Coupon',
               ]);

    if ($validator->fails()) {
        return response()->json([
         'status'    => false,
          'message'   => 'The following fields are required',
          'error'     => $validator->errors(),
          ], 200);
    }

    $coupondetails=Coupons::where('cm_code',$request->coupon_code)->first();
    if(!empty($coupondetails)){
        $usage=$coupondetails->cm_usage_limit - $coupondetails->cm_usage_count;
        if($usage>0){
            try{
                $discount =applyCoupon($coupondetails,$request->coupon_code);
                return response()->json([
                    'status'    => true,
                    'discount_price' => $discount,
                    'message'   => 'Coupon valid',
                    'data'   => $coupondetails,
                ], 200);
            
            }catch(Throwable $th){
                return response()->json([
                    'status'    => false,
                    'message'   => $th->getMessage(),
                ], 200);
            }
        }
        else {
            return response()->json([
                'status'    => false,
                'message'   => 'Coupon Usage Limit Reached',
            ], 200);    
        }

    }
    else {
        return response()->json([
            'status'    => false,
            'message'   => 'This coupon code is invalid or has expired.',
        ], 200);    
    }



}

public function orderList(){
$page=1;
$per_page=10;
$offset   = ( $page == 1 ) ? 0 : ( $page * $per_page ) - $per_page;
$query = Ordermaster::where('om_u_id',Auth::user()->c_id)->orderBy('orm_id','desc')->skip($offset)->limit( $per_page );

$list = $query->get();

if ($list) {
    return response()->json([ 
        'status' => true,
        'data'   => $list
    ], 200);
}

return response()->json([ 
    'status' => false,
    'data'   => 'Order created successfully!'
], 200);

}
public function orderDetails(Request $request){
$validator =Validator::make($request->all(),[
    'order_id' => 'required',
]);
if ($validator->fails()) {
    return response()->json([
        'status'    => false,
        'message'   => 'The following fields are required',
        'error'     => $validator->errors(),
    ], 200);
}

$orderdetails=Ordermaster::where('orm_id',$request->order_id)->first();
$items=Order::where('or_no',$orderdetails->om_or_id_no)->get();
if(empty($items)){
    return response()->json([ 
        'status' => false,
        'data'   => 'No record found'
    ], 200);
}
else{
       return response()->json([ 
            'status' => true,
            'image_url' => asset('uploads/products/'),
            'message' => 'Order found',
            'data'   => $items,
        ], 200);
    
}


}


}