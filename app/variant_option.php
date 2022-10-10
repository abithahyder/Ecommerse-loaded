<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variant_option extends Model
{
    use HasFactory;
    protected $table = 'variant_options';
    protected $fillable = ['vo_v_id', 'vo_p_id', 'vo_name'];
    protected  $primaryKey = 'vo_id';

    public function variant()
      { 
    return $this->belongsTo('App\variant', 'vo_v_id');
    
    }
}
