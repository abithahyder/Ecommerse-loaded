<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        // return ProductResource::collection(Product::all());
        $products =Product::all();
        return response()->json($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'productname' => 'required',
            'prodcut_category' =>'required',
            'actual_prize' => 'required',
            'seller_prize' => 'required',
            'discount' =>'required',
            'stock' =>'required',
            'productimg' => 'required'

           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'error']);
        }
        
        $pmodel =new Product();
        $pname=$request->productname;
        $pmodel->title=$request->productname;
         $pmodel->description=$request->description;
         $pmodel->product_category=$request->prodcut_category;
           $pmodel->quantity=$request->stock;
           $pmodel->actual_prize=$request->actual_prize;
           $pmodel->seller_prize=$request->seller_prize;
           $pmodel->discount=$request->discount;

           $image =$request->productimg;
           $imagename=time().'.'.$image->getClientOriginalExtension();
           $request->productimg->move('product',$imagename);
           $pmodel->product_image=$imagename;
             $res= $pmodel->save();

       return response()->json([
        "success" => true,
        "message" => " Product Inserted Successfully",
        "data" => $pname
        ]);
       
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product =Product::find($id);
        return response()->json($product);
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
    {  $data = $request->all();
        $validator = Validator::make($data, [
            'productname' => 'required',
            'prodcut_category' =>'required',
            'actual_prize' => 'required',
            'seller_prize' => 'required',
            'discount' =>'required',
            'stock' =>'required',
            'productimg' => 'required'

           
        ]);
        $pmodel = Product::find($id);
        $pname=$request->productname;
        $pmodel->title=$request->productname;
         $pmodel->description=$request->description;
         $pmodel->product_category=$request->prodcut_category;
           $pmodel->quantity=$request->stock;
           $pmodel->actual_prize=$request->actual_prize;
           $pmodel->seller_prize=$request->seller_prize;
           $pmodel->discount=$request->discount;

           $image =$request->productimg;
           $imagename=time().'.'.$image->getClientOriginalExtension();
           $request->productimg->move('product',$imagename);
           $pmodel->product_image=$imagename;
             $res= $pmodel->update();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pmodel=Product::find($id);
        $pname=$pmodel->title;
        $pmodel->delete();
        return response()->json([
            "success" => true,
            "message" => " Product Deleted Successfully",
            "data" => $pname
            ]);
    }
    public function searchpro($title){
        return Product::where("title","like","%",$title,"%")->get();

    }
}
