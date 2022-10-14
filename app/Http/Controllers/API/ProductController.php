<?php

namespace App\Http\Controllers\API;

use App\Client;
use App\delivery_charge;
use App\Http\Controllers\Controller;
use App\Product;
use App\product_review;
use App\sku_value;
use App\user_whishlist;
use App\view_counter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Plank\Mediable\Facades\MediaUploader;

class ProductController extends Controller
{
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'cat_id'     => 'nullable',
        ],[],['cat_id' => 'category']);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);

        }
        
        $page     = request('page',1);
        $per_page = request('per_page',12);
        $offset   = ( $page == 1 ) ? 0 : ( $page * $per_page ) - $per_page;
        $category = request('cat_id',0);
        $query = Product::withCount(['userLiked','reviews','viewerCount as viewer_count','viewerCount15Days'])
                            ->skip($offset)->limit( $per_page )->where('p_status','active');
        
        if ( $category > 0 ) {
            $query->where('p_cat_id',$category);
        }

        if ( $request->has('sort_popularity') ) {
            $query->orderBy('viewer_count','desc');
        }
        
        if ( $request->has('home_page') ) {
            $query->orderBy('viewer_count15_days_count','desc');
        }
        
        if ( $request->has('sort_price') ) {
            if( request('sort_price') == 'low' ){
                $query->orderBy('products.p_sale_price','asc');
            }else if( request('sort_price') == 'high' ){
                $query->orderBy('products.p_sale_price','desc');
            }
        }else{
            $list = $query->orderBy('products.p_id','desc');
        }
        $list = $query->get();

        if ($list) {
            foreach ($list as $key => $value) {
                $list[$key]->rating_avg = $value->avgReviewRating();
                if($value->hasMedia('product')){
                    $list[$key]->image = $value->getMedia('product')->first()->getUrl();
                }else{
                    $list[$key]->image = null;
                }
            }
            return response()->json([ 
                'status' => true,
                'data'   => $list
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);


    }

    public function productdetails(Request $request){
        $validator = Validator::make($request->all(), [
            'p_id'     => 'required|numeric',
        ],[],['p_id' => 'Product']);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }
        
        $list = Product::where('p_id',$request->p_id)->with(['subcategory','LatestReviews','variant.option'])->withCount(['userLiked','reviews'])->first();
        if ($list) {
            $list->image = [];
            if( $list->hasMedia('product') ){
                $images = [];
                foreach ($list->getMedia('product') as $key) {
                    $images[] =  $key->getUrl();
                }
                $list->image = $images;
            }
            $list->rating_avg = $list->avgReviewRating();
            $list->reviews()->limit(10);
           
            return response()->json([ 
                'status' => true,
                'data'   => $list
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);
   
   
   
    }

    public function getProductVariantPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_id'  => 'required|numeric',
            'vo_id' => 'required|array',
        ],[],[
            'p_id'  => 'Product',
            'vo_id' => 'Option'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }

        $options_count = count( $request->vo_id );
        $sku = sku_value::whereIn('skuv_vo_id',$request->vo_id)
                ->where('skuv_p_id',$request->p_id)
                ->groupBy('skuv_sku_id')
                ->havingRaw( 'count( skuv_id ) = ' . $options_count )
                ->first();
        
        if( !empty( $sku ) ){
            return response()->json([
                'status'  => true,
                'data'    => $sku->sku,
                'message' => 'Data found',
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No data found',
        ], 200);
    }
    public function addwhishlist(Request $request){
        $validator = Validator::make($request->all(), [
            'p_id'     => 'required',
            'p_vo_id' =>'nullable|array',
            'sku' =>'nullable'
        ]);

       if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }
       if (!Product::find( $request->p_id )) {
            return response()->json([
                'status'    => false,
                'message'   => 'Product not found',
            ], 200);
        }
         $client = Auth::user();
        if($client==""){
            $client=Client::findorFail(1);
        }
          
        
        $new = new user_whishlist();
        $new->uw_c_id = $client->c_id;
        $new->uw_p_id = $request->p_id;
        $new->p_vo_id =$request->p_vo_id;
        $new->sku=$request->sku;
        $new->save();

        if( $new ){
            return response()->json([
                'status'     => true,
                'inWishList' => true,
                'message'    => 'Wish list has been add successfully',
            ], 200);
        }
        else{
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong'
            ], 200);
        }

    }
    public function getWishListProduct(Request $request)
    {   
        $page     = request('page',1);
        $per_page = request('per_page',12);
        $offset   = ( $page == 1 ) ? 0 : ( $page * $per_page ) - $per_page;

        $client = Auth::user();

        $list = user_whishlist::select('product_master.*')
                            ->join('client_master','user_wishlists.uw_c_id','client_master.c_id')
                            ->join('product_master','user_wishlists.uw_p_id','product_master.p_id')
                            ->where('uw_c_id',$client->c_id)
                            ->skip($offset)->limit( $per_page )->orderby('uw_id','desc')->get();
        if ($list) {
            foreach ($list as $key => $value) {
                $product = Product::withCount(['userLiked','reviews'])->find($value->p_id);
                $list[$key]->rating_avg = $product->avgReviewRating();
                $list[$key]->reviews_count = $product->reviews_count;
                if($product->hasMedia('product')){
                    $list[$key]->image = $product->getMedia('product')->first()->getUrl();
                }else{
                    $list[$key]->image = null;
                }
            }
            return response()->json([ 
                'status' => true,
                'data'   => $list
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);
    }


    public function addReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pr_rating'   => 'required|numeric',
            'pr_review'   => 'required',
            'pr_p_id'     => 'required|numeric',
        ],[],[
            'pr_rating'   => 'Rating',
            'pr_review'   => 'Review',
            'pr_p_id'     => 'Product',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }

        $client = Auth::user();
        if($client==""){
            $client=Client::findorFail(1);
        }
        $params = $validator->validate();
        $params['pr_c_id'] = $client->c_id;

        $review = New product_review();
        $review->fill( $params );
        $review->save();
        if( $review ){

            if( $request->file('images') ){
                $mid = [];
                foreach ($request->file('images') as $item) {
                    $media = MediaUploader::fromSource($item)->toDirectory('products_review')->useHashForFilename()->upload();
                    array_push($mid, $media->getkey());
                }
                $review->attachMedia($mid, ['product_review']);
            }

            return response()->json([
                'status'    => true,
                'message'   => 'Review Added'
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'Something went wrong, please try again.'
        ], 200);
    } 
 
    public function ProductReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_id'     => 'required|numeric',
        ],[],['p_id' => 'Product']);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }

        $page     = request('page',1);
        $per_page = request('per_page',12);
        $offset   = ( $page == 1 ) ? 0 : ( $page * $per_page ) - $per_page;
        $product_id = request('p_id');

        $list = product_review::with('user')->where('pr_p_id',$product_id)->skip($offset)->limit( $per_page )->orderBy('pr_id','desc')->get();
        if ($list) {
            foreach ($list as $key => $value) {
                if($value->hasMedia('product_review')){
                    $images = [];
                    foreach ($value->getMedia('product_review') as $image) {
                        $images[] =  $image->getUrl();
                    }
                    $list[$key]->image = $images;
                }else{
                    $list[$key]->image = [];
                }
            }
            return response()->json([ 
                'status' => true,
                'data'   => $list
            ], 200);
        }

        return response()->json([
            'status'    => false,
            'message'   => 'No records found'
        ], 200);

    }

    public function viewCounter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'p_id'     => 'required|numeric',
        ],[],['p_id' => 'Product']);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => 'The following fields are required',
                'error'     => $validator->errors(),
            ], 200);
        }

        try {
            $client = Auth::user();
            $viewCounter = view_counter::create([
                'vc_p_id'    => $request->p_id,
                'vc_counter' => 1,
                'vc_c_id' => $client->c_id,
                'vc_date'    => date('Y-m-d'),
            ]);
    
            if( $viewCounter ){
                return response()->json([
                    'status'    => true,
                    'message'   => 'Count added',
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Something went wrong, Please try again.',
                '_error'   => $th->getMessage(),
            ], 200);
        }

    }
    public function availablepin(Request $request){
        $validator = Validator::make($request->all(), [
            'zip'   => 'required',
            'p_id' => 'required'
                  
        ],[],[
            'zip'   => 'Post Code',
            'p_id' => 'Product'
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
            if(!empty($data)){
               $product=Product::where('p_id',$request->p_id)->first();
               $str=explode(',',$product->p_availability);
               $available=in_array($data->dc_id, $str);
               return response()->json([
                'status'=>true,
                'message' =>'Product Available at the pincode',
                'delivery charge' => $data->dc_price
               ]);
            }
            else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Product Not Available in the pincode',
                                ], 200);
            }
           
    
        }catch(\Throwable $th){
            return response()->json([
                'status'  => false,
                'message' => 'Some error occured Try again',
                'erorr' => $th->getMessage()
            ], 200);
        }
    
    }

}
