<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected  $primaryKey = 'or_id';

    public function product(){
        return $this->belongsTo('App\Product', 'p_id');
    }
    public function Orderm()
    {
    	return $this->belongsTo('App\Ordermaster');
    }
    public function skus(){
        return $this->belongsTo('App\skus','sku','sku_id');
    }
   
}
