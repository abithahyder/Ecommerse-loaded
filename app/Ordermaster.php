<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordermaster extends Model
{
    use HasFactory;

    protected  $primaryKey = 'orm_id';
    public function user(){
        return $this->belongsTo('App\Client','om_u_id');
    }
    public function items(){
        return $this->hasMany('App\Order','invoice_number','or_invoice_number');
    }
    public function product(){
        return $this->belongsTo('App\Product', 'p_id');
    }

}
