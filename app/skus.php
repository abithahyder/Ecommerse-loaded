<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class skus extends Model
{
    use HasFactory;
    protected $table = 'skuses';
    protected $fillable = ['sku_name', 'sku_p_id', 'sku_price','sku_qty'];
    protected  $primaryKey = 'sku_id';

    public function sku()
    {
        return $this->hasMany('App\sku_value','skuv_sku_id','sku_id');
    }
}
