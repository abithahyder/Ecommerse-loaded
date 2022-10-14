<?php

namespace App\Http\Controllers\API;

use App\cart;
use App\Client;
use App\Http\Controllers\Controller;
use App\Product;
use App\skus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cart = cart::all();
        return response()->json($cart);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewcart()
    {
        $id =Auth::user()->c_id;
        $cart = cart::where('user_id','=',$id)->get();
        if($cart==""){
            return response()->json([
                'status'=>false,
                'message' =>'Cart is empty'
            ]);
        }
        else{
            
            return response()->json([
                'status'=>true,
                'message' =>'Cart  have Items',
                'data' => [$cart]
            ]);
        }
    }

    
    public function addCart(Request $request){
            
        
        $validator = Validator::make($request->all(), [
            'p_id'   => 'required|numeric',
            'taken_qty'   => 'required',
            'p_vo_id' =>'nullable|array',
            'sku' =>'nullable'
        ],[],[
            'p_id'   => 'Product',
            'taken_qty'   => 'Quantity',
            'p_vo_id' =>'Variants',
             'sku' =>'Product Variant'
           
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }
        $param=$validator->validate();
        $id=$param['p_id'];
        
           $user=Auth::user();
       
           $product=Product::find($id);
           if($product==""){
            return response()->json([
                'status'    => false,
                'message'   => 'product Not Exist|Invalid',
                'error'     => $validator->errors(),
            ], 200);
           }
           else{
                $check=Cart::where('p_id',$request->p_id)->first();
                    if(!empty($check)){
                        if($check->sku!="" && $check->sku==$request->sku){
                            $gsku=skus::where('sku_id',$request->sku)->first(); 
                                if($request->taken_qty<=$gsku->sku_qty && $check->taken_qty+$request->taken_qty<=$gsku->sku_qty){
                                    $check->taken_qty=$check->taken_qty+$request->taken_qty;
                                }  
                                else{
                                return response()->json([
                                'status'    => false,
                                'message'   => 'product Out of Stock',
                                'error'     => $validator->errors(),
                                 ], 200);
                                }
                 

                        }
                        else{
                             if($check->product->p_stock>=$request->taken_qty && $check->taken_qty+$request->taken_qty<=$check->product->p_stock){
                               $check->taken_qty=$check->taken_qty+$request->taken_qty;
                      
                             }
                              else{
                               return response()->json([
                                'status'    => false,
                                'message'   => 'product Out of Stock',
                                'error'     => $validator->errors(),
                                'data' =>$check,
                                 ], 200);
                              }
                   
                    
                        }
                        $check->update();
                        return response()->json([
                        'status'    => true,
                        'message'   => 'product Already Exist in Cart Quantity Updated',
                       ], 200);
                    }
            
                else{
                        if($product->p_multi_option!=0 && $request->has('p_vo_id') && $request->has('sku')){
                            $cartmodel = new Cart();
                            $cartmodel->p_id =$id;
                            $cartmodel->user_id=$user->c_id;
                            $tt=$request->sku;
                            $gsku=skus::where('sku_id',$tt)->first(); 
                            $qty=$gsku->sku_qty;
                               $p_vo_id =implode(',',$request->p_vo_id);
                            if($request->taken_qty<=$qty){
                                $cartmodel->taken_qty=$request->taken_qty;
                                $cartmodel->p_vo_id=$p_vo_id;
                                $cartmodel->sku=$request->sku;
                                $cartmodel->save();
                            }
                            else{
                                return response()->json([
                                    'status'    => true,
                                    'message'   => 'product Out of stock try again later',
                                    'error'     => $validator->errors(),
                                    
                                ], 200);
                                        
                            }
                            return response()->json([
                                'status'    => true,
                                'message'   => 'product added to cart',
                                'error'     => $validator->errors(),
                            ], 200);
                        
                        }
                        else{
                            if($product->p_multi_option==0){
                            $cartmodel = new Cart();
                            $cartmodel->p_id =$id;
                            $cartmodel->user_id=$user->c_id;
                            if($request->taken_qty<=$product->p_stock){
                                $cartmodel->taken_qty=$request->taken_qty; 
                                $cartmodel->save();
                            }
                        
                        
                            return response()->json([
                                'status'    => true,
                                'message'   => 'product added to cart',
                                'error'     => $validator->errors(),
                            ], 200);

                        } 
                    
                        }
                    
                        return response()->json([
                            'status'    => true,
                            'message'   => 'product have many Variants Select Appropriate',
                            'error'     => $validator->errors(),
                        ], 200);
                
            }
     }
            
        
    }
    
     public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeCartItem(Request $request){
        $validator = Validator::make($request->all(), [
            'cart_id'   => 'required|numeric',
                  
        ],[],[
            'cart_id'   => 'Cart Item',
                   ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }
        $cart = cart::find($request->cart_id);
        $cart->delete();
        return response()->json([
            'status'    => true,
            'message'   => 'The Item deleted from cart',
            'error'     => $validator->errors(),
        ], 200);
    }
    public function destroy(Request $request)
    {
       
    }
    public function updatecart(Request $request){
        $validator = Validator::make($request->all(), [
            'cart_id'   => 'required|numeric',
              'taken_qty' => 'required',    
        ],[],[
            'cart_id'   => 'Cart Item',
            'taken_qty' => 'Taken Qty',
                   ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }
        $id=$request->cart_id;
        $cart = cart::find($id);
        $cart->taken_qty=$request->taken_qty;
        $cart->update();
        return response()->json([
            'status'    => true,
            'message'   => 'The Item Quantity Updated in cart',
            'error'     => $validator->errors(),
        ], 200);
    }

   
}
