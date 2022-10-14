<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_whishlist extends Model
{
    use HasFactory;
   
    protected $fillable = ['uw_c_id', 'uw_p_id'];
    protected  $primaryKey = 'uw_id';

    public function user(){
        return $this->belongsTo('App\ClientModel','uw_c_id','c_id');
    }
     
    public function product(){
        return $this->belongsTo('App\ProductModel','uw_p_id','p_id');
    }
}
