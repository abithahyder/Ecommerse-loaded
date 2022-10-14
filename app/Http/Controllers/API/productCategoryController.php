<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class productCategoryController extends Controller
{
    public function index()
    {
        

        $page = request('page',1);
        $per_page= request('per_page',12);
        $offset   = ( $page == 1 ) ? 0 : ( $page * $per_page ) - $per_page;
        $category = request('id',0);
        $pcat = subcategory::where('status','active')->get();
        if($pcat){
           
        
            return response()->json([
                'status'    => true,
                'image_url' => 'uploads/subcategory/',
                'data'   => $pcat
            ], 200);
        }
        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 20);
      
       
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
            'product_categoryname'=>'required',
            'pcatimg'=>'required',
            'cid' => 'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'error']);
        }

        $pcatmodel =new subcategory();
            $pcatname=$request->product_categoryname;
            $pcatmodel->Product_category_name = $pcatname ;
             $pcatmodel->cid =$request->cid;
             $image =$request->pcatimg;
             $imagename=time().'.'.$image->getClientOriginalExtension();
             $request->pcatimg->move('productcategory',$imagename);
             $pcatmodel->product_category_image = $imagename;
             $res= $pcatmodel->save();

       return response()->json([
        "success" => true,
        "message" => " Product Category Inserted Successfully",
        "data" => $pcatname
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
        $pcat =subcategory::find($id);
         return response()->json($pcat);
        
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
        $pcatmodel =new subcategory();
            $pcatname=$request->product_categoryname;
            $pcatmodel->Product_category_name = $pcatname ;
             $pcatmodel->cid =$request->cid;
             $image =$request->pcatimg;
             $imagename=time().'.'.$image->getClientOriginalExtension();
             $request->pcatimg->move('productcategory',$imagename);
             $pcatmodel->product_category_image = $imagename;
                  $res= $pcatmodel->update();
                  return response()->json([
                    "success" => true,
                    "message" => " Product Category Updated Successfully",
                    "data" => $pcatname
                    ]);
                }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pcatmodel=subcategory::find($id);
        $pcatname=$pcatmodel->Product_category_name;
        $pcatmodel->delete();
        return response()->json([
            "success" => true,
            "message" => " Product Category Deleted Successfully",
            "data" => $pcatname
            ]);

    }

    
}
