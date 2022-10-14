<?php

namespace App\Http\Controllers;

use App\Category;
use App\subcategory as productcategory;
use App\subcategory as AppSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;

class SubCategory extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return view('sub-category.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($id=null)
    {
        $Category = Category::all();
        $data = null;
        if( !empty($id) ){
            $data = productcategory::findorFail($id); 
        }
        return view('sub-category.add',compact('Category','data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated =$request->validate([
            'title' => 'required',
            'parent_id' =>'required',
            'scat_image' =>'required'

        ]);
        $post = array(
            'Product_category_name'      => $request->title,
            'cid' => $request->parent_id,
                    );
        if (request('edit_id')){
           productcategory::where('id', $request->edit_id)->update($post);
            if($request->scat_image){
                $id=$request->edit_id;
                $pcatmodel = productcategory::findorFail($id);
                if($pcatmodel->product_category_image!=""){
                    $image_path="uploads/subcategory/$pcatmodel->product_category_image";
                    if (File::exists($image_path)) {
                     File::delete($image_path);
                    }
                    
                    $image =$request->scat_image;
                     $imagename=time().'.'.$image->getClientOriginalExtension();
                     $request->scat_image->move('Uploads\subcategory',$imagename);
                     $pcatmodel->product_category_image = $imagename;
                     $res= $pcatmodel->update();
           
           
                }                   
        
        }
           }
        else{
            $pcatmodel =new productcategory();
            $pcatname=$request->title;
            $pcatmodel->Product_category_name = $pcatname ;
             $pcatmodel->cid =$request->parent_id;
             $image =$request->scat_image;
             $imagename=time().'.'.$image->getClientOriginalExtension();
             $request->scat_image->move('Uploads\subcategory',$imagename);
             $pcatmodel->product_category_image = $imagename;
             $res= $pcatmodel->save();
        }
      return redirect()->back()->with('success','Product Category Added Successfully');
    
   
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
    public function delete(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }

      $pcatmodel=productcategory::findorFail(request('id'));
     $delete= $pcatmodel->delete();
      echo ($delete > 0) ? true :  false;
    }
    public function getdata(Request $request)
    {
        if(!$request->ajax())
        return abort(404);

        $subcategory = productcategory::select('id','cid','Product_category_name','product_category_image','status')->orderBy('id','desc');

        return DataTables::of($subcategory)
            ->addColumn('action', function ($subcategory) {
            $data = null;
                $data .= '<a href="'.route( 'subcategory.edit' , $subcategory->id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                        <i class="la la-edit"></i>
                    </a>';
                $data .= '<a href="javascript:;" data-id="'.$subcategory->id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                        <i class="la la-trash"></i>
                    </a>';
            return $data;
        })
        ->addColumn('parent', function ($subcategory) {
            return !empty($subcategory->category->category_name) ? '<a href="'.route( 'subcategory.edit' , $subcategory->category->category_name).'" class="" title="Edit details"> '. $subcategory->category->category_name.' </a>'  : null;
        })
         ->addColumn('image', function ($subcategory) {
            if($subcategory->product_category_image!=null){
                return '<img src="uploads/subcategory/'.$subcategory->product_category_image.'" class="img-thumbnail h-auto" style="width: 55px !important; " alt="No image found">';
            }
            return null;
        })
        ->addColumn('status', function ($subcategory) {
            return '<span class="kt-switch kt-switch--sm kt-switch--icon kt-switch--success kt-switch--outline">
                        <label>
                            <input type="checkbox" '.(check_permission('adminEdit') ? '' : 'disabled').' class="chkbox_active"  '. ($subcategory->status == 'active' ? 'checked' : '') .' value="'.(check_permission('adminEdit') ? $subcategory->id : '').'">
                            <span></span>
                        </label>
                    </span>';
        })
        
        ->rawColumns(['action' => 'action', 'parent'=>'parent' ,'image' => 'image','status'=>'status'])
        ->make(true);
    }
    public function statusChange(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }
        $affected = productcategory::where('id', request('id'))->update(array('status' => request('status')));
        echo ($affected > 0) ? true :  false;
    }
}
