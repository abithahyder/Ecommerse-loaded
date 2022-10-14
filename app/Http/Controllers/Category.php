<?php

namespace App\Http\Controllers;

use App\Category as Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\DataTables;
use Plank\Mediable\Facades\MediaUploader;
class Category extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('category.list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add($id = null)
    {
        $data = null;
        if( !empty($id) ){
            $data = Categories::findorFail($id); 
        }
        
        return view('category.add',compact('data' ));
       
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'cat_name' => 'required',
            'cat_image'     => 'nullable',
        ]);
        $post = array(
            'category_name'      => $request->cat_name,
                    );
                    if (request('edit_id')){
                        Categories::where('id', $request->edit_id)->update($post);
                        if($request->cat_image){
                            $id=$request->edit_id;
                           $catmodel= Categories::findorFail($id);
                           if($catmodel->category_image!=""){
                            $image_path="public/uploads/category/$catmodel->category_image";
                            if (File::exists($image_path)) {
                              unlink($image_path);
                            }
                            $catname=$request->cat_name;
                            $image =$request->cat_image;
                            $imagename=$catname.'.'.$image->getClientOriginalExtension();
                             $destinationPath = public_path('uploads/category');
                            $request->cat_image->move($destinationPath ,$imagename);
                            $catmodel->category_image = $imagename;
                            $catmodel->update();
                           }
                        }

                    }
                    else{
                        $catmodel =new Categories();
                        $catname=$request->cat_name;
                        $catmodel->category_name = $catname;
                        $image =$request->cat_image;
                        $imagename=$catname.'.'.$image->getClientOriginalExtension();
                        $destinationPath = public_path('uploads/category');
                        $request->cat_image->move($destinationPath ,$imagename);
                        $catmodel->category_image = $imagename;
                        $catmodel->save();
                    }
                   
         
             return redirect()->back()->with('success','Category Added Successfully');
          
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
    public function edit(Request $request,$id)
    {
        
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
    public function destroy(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }

      $catmodel=Categories::findorFail(request('id'));
      $delete=$catmodel->delete();
      echo ($delete > 0) ? true :  false;

    }
    public function getdata(Request $request)
    {
        if(!$request->ajax())
        return abort(404);

        $category = Categories::select('id','category_name','category_image','status')->orderBy('id','desc');

        return DataTables::of($category)
            ->addColumn('action', function ($category) {
            $data = null;
                $data .= '<a href="'.route( 'category.edit' , $category->id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                        <i class="la la-edit"></i>
                    </a>';
                $data .= '<a href="javascript:;" data-id="'.$category->id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                        <i class="la la-trash"></i>
                    </a>';
            return $data;
        })
         ->addColumn('image', function ($category) {
            if($category->category_image!=null){
                return '<img src="uploads/category/'.$category->category_image.'" class="img-thumbnail h-auto" style="width: 55px !important; " alt="No image found">';
            }
            return null;
        })
        ->addColumn('status', function ($category) {
            return '<span class="kt-switch kt-switch--sm kt-switch--icon kt-switch--success kt-switch--outline">
                        <label>
                            <input type="checkbox" '.(check_permission('adminEdit') ? '' : 'disabled').' class="chkbox_active"  '. ($category->status == 'active' ? 'checked' : '') .' value="'.(check_permission('adminEdit') ? $category->id : '').'">
                            <span></span>
                        </label>
                    </span>';
        })
        ->rawColumns(['action' => 'action', 'image' => 'image','status'=>'status'])
        ->make(true);
    }
    public function statusChange(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }
        $affected = Categories::where('id', request('id'))->update(array('status' => request('status')));
        echo ($affected > 0) ? true :  false;
    }
}
