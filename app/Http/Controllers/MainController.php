<?php

namespace App\Http\Controllers;

use App\cart;
use App\Client;
use App\ClientAddress;
use App\country;
use App\Coupons;
use App\delivery_charge;
use App\Order;
use App\Ordermaster;
use App\Product;
use App\states;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index(){
        //    if(Auth::user()){
        //     return redirect('redirect');
        //    }
        //     else
        //      {
                $product =Product::paginate(10);
                return view('home.userpage',compact('product'));
            //  }
           
        }
        
        public function redirect(){
            $usertype = Auth::user()->usertype;
              if($usertype == 'administrator'){
                   return view('home');
               }
            else{
                $usertype = Auth::user();
                $product =Product::paginate(10);
                return view('home.userpage',compact('product'));
              }
        }
        public function product_details($id){
            $product = Product::find($id);
            return view('home.product_details',compact('product'));
     
        }
    
        public function add_to_cart(Request $request,$id){
            if(Auth::id()){
               $user=Auth::user();
               $product=Product::find($id);
               $cartmodel = new Cart();
               $cartmodel->p_id =$request->id;
               $cartmodel->user_id=$user->id;
               $cartmodel->taken_qty=$request->qty;
               $cartmodel->save();
               return redirect()->back()->with('message','Product Added to Cart Successfully');
    
            }
            else{
                return redirect('login');
            }
        }
    
    public function view_cart(){
        $id =Auth::user()->id;
        $cart = cart::where('user_id','=',$id)->get();
        return view('home.view_cart',compact('cart'));
    }
    
    public function remove_cart($id){
        $cart = cart::find($id);
        $cart->delete();
        return redirect()->back();
    }
    public function cash_order(){
        $user=Auth::user();
        $userid=$user->id;
        $data =Cart::where('user_id','=',$userid)->get();
        $orderinvoice=DB::table('orders')->latest()->first();
       $total_items=0;
       $total_price=0;
       date_default_timezone_set("Asia/Calcutta");
       $date =date("Y-m-d H:i:s");
        foreach($data as $data){
            $total_items++;
            $total_price=$total_price+$data->product->p_sale_price;
            $ordermodel = new Order();
           
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
               
               
            $ordermodel->invoice_number	=$invoice;
            $ordermodel->or_no =$orderno;
            $ordermodel->u_id=$data->user_id;
            $ordermodel->p_id=$data->p_id;
            $ordermodel->price=$data->product->p_sale_price;
            $ordermodel->qty=$data->taken_qty;
            $ordermodel->payment_mode="";
            $ordermodel->pay_status="pending";
            $ordermodel->delivery_status="processing";
            $ordermodel->oim_image=$data->product->getMedia('product')->first()->getDiskPath();
            $ordermodel->save();
            $cartid=$data->cart_id;
            $cart=Cart::findorFail($cartid);
            $cart->delete();

         }
              $ordermas=new Ordermaster();
               $ordermas->om_u_id=$userid;
               $ordermas->om_or_id_no=$orderno;
               $ordermas->om_date=$date;
               $ordermas->om_status="pending";
               $ordermas->om_total=$total_price;
               $ordermas->total_items=$total_items;
               $ordermas->or_invoice_number=$invoice;
             $ordermas->save();
        
        return redirect()->back()->with('message','We have Received your order.we will connect with you soon...');
    }
    public function checkout(){
        $id =Auth::user()->id;
        $cart = cart::where('user_id','=',$id)->get();
        $count = cart::where('user_id','=',$id)->count();
        return view('home.checkout',compact('cart','count'));
    }

    public function checkoutsave(Request $request){
       
        $user=Auth::user();
        $userid=$user->id;
        $data =Cart::where('user_id','=',$userid)->get();
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
            $total_price=$total_price+$data->product->p_sale_price;
            $ordermodel = new Order();
                         
            $ordermodel->invoice_number	=$invoice;
            $ordermodel->or_no =$orderno;
            $ordermodel->u_id=$data->user_id;
            $ordermodel->p_id=$data->p_id;
            $ordermodel->price=$data->product->p_sale_price;
            $ordermodel->qty=$data->taken_qty;
            $ordermodel->name=$data->product->p_name;
            $ordermodel->payment_mode=$request->paymentMethod;
            $ordermodel->pay_status="pending";
            $ordermodel->delivery_status="processing";
            $ordermodel->oim_image=$data->product->getMedia('product')->first()->getDiskPath();
            $ordermodel->save();
            $cartid=$data->cart_id;
            $cart=Cart::findorFail($cartid);
            $cart->delete();

         }
        //  $chargem=delivery_charge::where('dc_postcode','=',$request->spost)->first();
        $sid=$request->spost;
        $chargem=DB::table('delivery_charges') ->where('dc_postcode', '=', $sid)->first();
        if($chargem!=""){
            $charge=$chargem->dc_price;
        }
        else{
            $charge=0;
        }
            
            $grand_total = $total_price + $charge;
              $ordermas=new Ordermaster();
               $ordermas->om_u_id=$userid;
               $ordermas->om_or_id_no=$orderno;
               $ordermas->om_date=$date;
               $ordermas->om_status="pending";
               $ordermas->om_total=$total_price;
               $ordermas->total_items=$total_items;
               $ordermas->or_invoice_number=$invoice;
               $ordermas->om_addresline_1=$request->saddrss1;
               $ordermas->om_addresline_2=$request->saddrss2;
               $ordermas->om_mobile=$request->snum1;
               $ordermas->om_city=$request->scity;
               $ordermas->om_state=$request->sstate;
               $ordermas->om_sname=$request->sname;
               $ordermas->om_country=$request->scountry;
               $ordermas->om_grand_total=$grand_total;
               $ordermas->payment=$request->paymentMethod;
               $ordermas->delivery_charge=$charge;
               $ordermas->save();
               
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
                
                return redirect()->back()->with('message' , 'Something went wrong '.$th->getMessage());
            }
            DB::commit();
                      
        return redirect()->back()->with('message','We have Received your order Please Continue to Checkout for Order Confirmation');
    }

    public function placeorder(Request $request){
       
        $user=Auth::user();
        $userid=$user->id;
        $data =Cart::where('user_id','=',$userid)->get();
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
            $total_price=$total_price+$data->product->p_sale_price;
            $ordermodel = new Order();
                         
            $ordermodel->invoice_number	=$invoice;
            $ordermodel->or_no =$orderno;
            $ordermodel->u_id=$data->user_id;
            $ordermodel->p_id=$data->p_id;
            $ordermodel->price=$data->product->p_sale_price;
            $ordermodel->qty=$data->taken_qty;
            $ordermodel->name=$data->product->p_name;
            $ordermodel->payment_mode="";
            $ordermodel->pay_status="pending";
            $ordermodel->delivery_status="processing";
            $ordermodel->oim_image=$data->product->getMedia('product')->first()->getDiskPath();
            $ordermodel->save();
            $cartid=$data->cart_id;
            $cart=Cart::findorFail($cartid);
            $cart->delete();

         }
        //  $chargem=delivery_charge::where('dc_postcode','=',$request->spost)->first();
        $sid=$request->spost;
        $chargem=DB::table('delivery_charges') ->where('dc_postcode', '=', $sid)->first();
        if($chargem!=""){
            $charge=$chargem->dc_price;
        }
        else{
            $charge=0;
        }
            
            $grand_total = $total_price + $charge;
              $ordermas=new Ordermaster();
               $ordermas->om_u_id=$userid;
               $ordermas->om_or_id_no=$orderno;
               $ordermas->om_date=$date;
               $ordermas->om_status="ordering";
               $ordermas->om_total=$total_price;
               $ordermas->total_items=$total_items;
               $ordermas->or_invoice_number=$invoice;
               $ordermas->om_grand_total=$grand_total;
               $ordermas->payment="";
               $ordermas->delivery_charge=$charge;
               $ordermas->save();
            }catch (\Throwable $th) {
                DB::rollback();
                
                return redirect()->back()->with('message' , 'Something went wrong '.$th->getMessage());
            }
            DB::commit();
                      
        return redirect()->checkout()->with('message','We have Received your order Please Continue to Checkout for Order Confirmation');
    }
    

    public function applycoupon(Request $request){
        if($request->reedemcode){
            $coupon=$request->coupon_code;
            $coupondetails=Coupons::where('cm_code',$coupon)->first();
            if(!empty($coupondetails)){
              $usage=$coupondetails->cm_usage_limit - $coupondetails->cm_usage_count;
              if($usage>0){
               $discount =applyCoupon($coupondetails,$request->coupon_code);
               $orderm=Ordermaster::findorFail($request->ormid);
               $grand_total=$orderm->om_grand_total;
               $orderm->om_discount=$orderm->om_discount+$discount;
               $orderm->om_grand_total=$grand_total-$discount;
               $orderm->update();
              }
          }
        }
        return redirect()->back();
   
    
       }
}
