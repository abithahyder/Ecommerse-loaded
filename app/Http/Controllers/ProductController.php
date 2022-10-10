<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\sku_value;
use App\skus;
use App\subcategory;
use App\variant;
use App\variant_option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Plank\Mediable\Facades\MediaUploader;
use Yajra\DataTables\DataTables;

class ProductController extends Controller
{
    public function index(){
        $categoryList = Category::all();
               
        return view('product.list',compact( 'categoryList'));
    }
        public function add( $id = null )
        {    
            $data = null;
            if( !empty($id) ){
                $data = Product::findorFail($id); 
            }
    
            $categoryList = Category::all();
            $subcategoryList = subcategory::all();
            return view('product.add',compact( 'data', 'categoryList', 'subcategoryList'));
        }
    
        public function subCategory(Request $request)
        {
            $subCategoryList = subcategory::where('cid', request('id'))->get()->toArray();
            if( !empty($subCategoryList) ){
                return response()->json([
                    'status' => true,
                    'data'   => $subCategoryList,
                ]);
            }
    
            return response()->json([
                'status' => false,
                'message' => 'No record found',
            ]);
        }

    
      public function save(Request $request)
    {
        $id = $request->edit_id;
        $validate = $request->validate([
            'p_name'   => 'required',
            'p_cat_parent_id'  => 'required',
            'p_cat_id'   => 'required',
            'p_price'  => 'required|numeric',
            'p_sale_price'   => 'required|numeric',
            'p_status'  => 'required',
            'p_short_desc'  => 'required',
            'p_desc'  => 'required',
            'p_multi_option'  => 'nullable',
            'p_stock' => 'required',
        ],[],[
            'p_name'   => 'name',
            'p_cat_parent_id'  => 'Category',
            'p_cat_id'   => 'Sub Category',
            'p_price'  => 'Price',
            'p_sale_price'   => 'Sale price',
            'p_status'  => 'status',
            'p_short_desc'  => 'Short description',
            'p_desc'  => 'description',
            'p_stock'=>'stock',
        ]);
        
        DB::beginTransaction();

        try {
            if (request('edit_id')) {
                $product = Product::find( $request->edit_id );
            }else{
                $product = New Product;
            }
            $product->fill($validate);
            $product->save();

            if( $request->file('images') ){
                $mid = [];
                foreach ($request->file('images') as $item) {
                    $media = MediaUploader::fromSource($item)->toDirectory('products')->useHashForFilename()->upload();
                    array_push($mid, $media->getkey());
                }
                $product->attachMedia($mid, ['product']);
            }

            if( !empty( $request->values ) && !empty( $request->preview ) ){ 

                /**
                 * Start product variant option
                */

                $newArray = [];
                
                foreach ($request->values as $value) {
                    array_push($newArray,$value);
                }
                
                $list = $this->combinations( $newArray );
                foreach ($request->option as $key => $value) {
                    $variantNew = array(
                        'v_p_id' => $product->p_id,
                        'v_name' => $value[0],
                    );
                    $variant = variant::create( $variantNew );
                    foreach ($request->values[$key] as $vkey => $vvalue) {
                        $variantValueNew = array(
                            'vo_v_id' => $variant->v_id,
                            'vo_p_id' => $product->p_id,
                            'vo_name' => $vvalue,
                        );
                        $variantOption = variant_option::create( $variantValueNew );
                    }
                }
                $skuList=array();
                $preview = $request->preview;
                foreach ($preview['price'] as $key => $value) {
                    
                    $skuNew = array(
                        'sku_p_id' => $product->p_id,
                        'sku_name' => $preview['sku'][$key],
                        'sku_price' => $preview['price'][$key],
                        'sku_qty' => $preview['qty'][$key],
                    );
                    $sku = skus::create($skuNew);
                    array_push( $skuList , $sku->sku_id);
                   
                }
                $sku_option_new = array();
                foreach ($list as $key => $value) {
                  foreach ($list as $lkey => $lvalue) {
                        print_r($lvalue);
                        $option = variant_option::where('vo_name',$lvalue)->where('vo_p_id',$product->p_id)->first();
                        $sku_option_new[] = array(
                            'skuv_sku_id' => $skuList[$key],
                            'skuv_p_id' => $product->p_id,
                            'skuv_vo_id' => $option->vo_id,
                            'skuv_v_id' =>$option->vo_v_id
                        );    
                    }
                }
                sku_value::insert($sku_option_new);

                /**
                 * End product variant option
                */
            }

        } catch (\Throwable $th) {
            DB::rollback();
            
            return redirect()->back()->with('message' , 'Something went wrong $th');
        }

        DB::commit();
        return redirect()->route('product.list')->with('message' , 'Product info save Successfully..!!!');
    }
    
       
    public function updateSku( Request $request )
    {
        if(!$request->ajax()){
            return abort(404);
        }
        $sku = array(
            'sku_name' => $request->sku,
            'sku_price' => $request->price,
            'sku_qty' => $request->qty,
        );
        $sku = skus::where('sku_id',$request->edit_id)->update( $sku );
        echo ($sku > 0) ? true :  false;
        
    }
    public function deleteVariant( Request $request )
    {
        if(!$request->ajax()){
            return abort(404);
        }
        $sku = skus::where('sku_id',$request->id)->delete();
        $skuValue = sku_value::where('skuv_sku_id',$request->id)->delete();
        echo ($skuValue > 0) ? true :  false;
        
    }
      
    public function getdata(Request $request)
    {
        if(!$request->ajax())
        return abort(404);

        $data = Product::select('p_id','p_cat_parent_id','p_cat_id', 'p_name','p_price','p_sale_price','p_status','child.Product_category_name as ctitle','parent.category_name as ptitle')
                            ->leftJoin('categories as parent','products.p_cat_parent_id','parent.id')
                            ->leftJoin('subcategories as child','products.p_cat_id','child.id');
        if( request('category') ){
            $data = $data->where('p_cat_parent_id',request('category'));
        }

        if( request('sub_category') ){
            $data = $data->where('p_cat_id',request('sub_category'));
        }

        return Datatables::of($data)
        ->addColumn('action', function ($data) {
            $action = null;
                $action .= '<a href="'.route( 'product.edit' , $data->p_id).'" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit details">
                        <i class="la la-edit"></i>
                    </a>';
                $action .= '<a href="javascript:;" data-id="'.$data->p_id.'" class="btn btn-sm delete-single btn-clean btn-icon btn-icon-md" title="Delete">
                        <i class="la la-trash"></i>
                    </a>';
            return $action;
        })
        ->addColumn('image', function ($data) {
            if($data->hasMedia('product')){
    return '<img src="uploads/'.$data->getMedia('product')->first()->getDiskPath().'" class="img-thumbnail h-auto" style="width: 55px !important; " alt="No image found">';
                 
            }
            return null;
        })
        ->rawColumns(['action' => 'action','image' => 'image'])
        ->make(true);
    }

    public function delete(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }
        $product = Product::find(request('id'));
        if( $product ){
            $product->detachMediaTags('product');
        }
        $delete =  $product->delete();
        echo ($delete > 0) ? true :  false;
    }
    public function imageDelete(Request $request)
    {
        if(!$request->ajax()){
            return abort(404);
        }
        // $media = Media::where('id',request('id'))->delete();
        // dd( $media );
        $product = Product::find( $request->product_id );
        $mediaCollection = $product->getMedia('product')->where('id',$request->id);
        foreach ($mediaCollection as $key => $value) {
            $value->delete();
        }
        // dd( $mediaCollection->delete() );
        echo true;
    }

       public function variantList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'array'  => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false ,
                'message'   => 'The following fields are required' ,
                'error'     => $validator->errors()
            ], 200);
        }

        try {
            $list = $this->combinations( $request->array );
            if( $list ){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Preivew list' ,
                    'data'      => $list
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'error'     => $th->getMessage()
            ], 200);
        }
    }

    private function combinations($arrays, $i = 0) {
        if (!isset($arrays[$i])) {
            return array();
        }
        if ($i == count($arrays) - 1) {
            return $arrays[$i];
        }
    
        $tmp = $this->combinations($arrays, $i + 1);
        $result = array();
        $stringResult = array();

        foreach ($arrays[$i] as $v) {
            foreach ($tmp as $t) {
                $arr = is_array($t) ? 
                        array_merge(array($v), $t) :
                        array($v, $t);
                $result[] = $arr;
                    
            }
        }
        // $stringResult[] = '['.implode("][", $result).']'; 
        return $result;
        // return compact('result','stringResult');
    }

  }

