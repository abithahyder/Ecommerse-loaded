<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordermaster extends Model
{
    use HasFactory;
    protected $fillable = [
        'om_u_id',
        'om_or_id_no',
        'om_date',
        'om_status',
        'om_total',
        'payment',
        'om_addresline_1',
        'om_addresline_2',
        'om_mobile',
        'om_city',
        'om_state',
        'om_country',
        'om_pincode',
        'om_discount',
        'om_grand_total',
        'delivery_charge',
        'or_invoice_number',
    ];
  
   

    protected  $primaryKey = 'orm_id';

    public function usr(){
        return $this->belongsTo('App\Client','om_u_id');
    }
    public function usraddrss(){
        return $this->belongsTo('App\ClientAddress','om_u_id','ca_client_id');
    }
    public function items(){
        return $this->hasMany('App\Order','invoice_number','or_invoice_number');
    }
   
   
    public function product(){
        return $this->belongsTo('App\Product', 'p_id');
    }

}
