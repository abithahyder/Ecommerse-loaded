<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sku_value extends Model
{
    use HasFactory;
    protected $table = 'sku-values';
    protected $fillable = ['skuv_sku_id', 'skuv_p_id', 'skuv_vo_id'];
    protected  $primaryKey = 'skuv_id';

    public function sku(){
        return $this->hasOne('App\skus','sku_id','skuv_sku_id');
    }
}
