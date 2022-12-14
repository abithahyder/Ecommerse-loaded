<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = request('page',1);
        $per_page= request('per_page',12);
        $offset   = ( $page == 1 ) ? 0 : ( $page * $per_page ) - $per_page;
        $category = request('id',0);
        $cat = Category::where('status','active')->get();
        if($cat){
           
        
            return response()->json([
                'status'    => true,
                'image_url' => 'uploads/category',
                'data'   => $cat
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
            'categoryname'=>'required',
            'catimg'=>'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors(), 'error']);
        }

       $catmodel = new Category();
       $catname =$request->categoryname;
       $image=$request->catimg;
       $imagename=$catname.'.'.$image->getClientOriginalExtension();
       $request->catimg->move('category',$imagename);
    
       $catmodel->category_image = $imagename; 
       $catmodel->category_name = $catname;
       $catmodel->save();
      

       
        return response()->json([
        "success" => true,
        "message" => "Category Inserted Successfully",
       
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
       $cat = Category::find($id);
        return response()->json($cat);
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
        $catmodel = Category::find($id);
        $catname =$request->categoryname;
        if($request->catimg){
         $image=$request->catimg;
         $imagename=$catname.'.'.$image->getClientOriginalExtension();
         $request->catimg->move('category',$imagename);
    
         $catmodel->category_image = $imagename; 
        }
        
        $catmodel->category_name = $catname;
        $catmodel->update();
       
 
        
         return response()->json([
         "success" => true,
         "message" => "Category Updated Successfully",
        
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
        $catmodel =Category::find($id);
        $catmodel->delete();
        return response()->json([
            "success" => true,
            "message" => "Category Deleted Successfully",
           
            ]);
    }
}
