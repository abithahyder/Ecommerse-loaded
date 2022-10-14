<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class view_counter extends Model
{
    use HasFactory;
    protected $table = 'view_counters';
    protected $fillable = ['vc_p_id', 'vc_date', 'vc_counter','vc_c_id'];
    protected  $primaryKey = 'vc_id';

    public function product(){
        return $this->hasOne('App\ProductModel','vc_p_id');
    }
}
