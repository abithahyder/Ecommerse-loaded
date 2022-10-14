<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Plank\Mediable\Mediable;

class Product extends Model
{
    use HasFactory;
    use Mediable;

    
    protected $table = 'products';
    protected $fillable = ['p_cat_parent_id', 'p_cat_id', 'p_name', 'p_desc', 'p_price', 'p_sale_price', 'p_status','p_short_desc','p_multi_option','p_stock','p_availability'];
    protected $primaryKey = 'p_id';

    protected $hidden = [
        'media'
    ];

    public function image()
    {   
        $product = $this;
        return ($product->hasMedia('product')) ? $product->getMedia('product')->first()->getUrl() : null;
    }

    public function category()
    {
       return $this->belongsTo('App\Category', 'id');
    }

    public function subcategory(){
        return $this->belongsTo('App\SubCategory', 'id');
    }

    public function reviews()
    {
        return $this->hasMany('App\product_review','pr_p_id','p_id')->orderBy('pr_id','desc');
    }

    public function viewerCount()
    {
        return $this->hasMany('App\view_counter','vc_p_id','p_id');
    }

    public function viewerCount15Days()
    {
        return $this->hasMany('App\view_counter','vc_p_id','p_id')->where('vc_date','>=',now()->subdays(15));
    }

    public function variant()
    {
        return $this->hasMany('App\variant','v_p_id','p_id');
    }

    public function variantOption()
    {
        return $this->hasMany('App\variant_option','vo_p_id','p_id');
    }

    public function sku()
    {
        return $this->hasMany('App\skus','sku_p_id','p_id');
    }

    public function skuOption()
    {
        return $this->hasMany('App\sku_value','skuv_p_id','p_id')->groupBy('skuv_sku_id');
    }


    public function LatestReviews()
    {
        return $this->hasMany('App\product_review','pr_p_id','p_id')->with('user')->limit(10)->orderBy('pr_id','desc');
    }

    public function userLiked()
    {
        return $this->hasMany('App\user_whishlist','uw_p_id','p_id')->where('uw_c_id',Auth::user()->c_id);
    }

    public function totalReview()
    {
        return count($this->reviews()->toArray());
    }

    public function avgReviewRating()
    {
        return $this->reviews()->avg('pr_rating');
    }
}
