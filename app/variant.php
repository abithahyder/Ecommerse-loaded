<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class variant extends Model
{
    use HasFactory;
    protected $table = 'variants';
    protected $fillable = ['v_p_id', 'v_name'];
    protected  $primaryKey = 'v_id';

    public function option()
    {
        return $this->hasMany('App\variant_option','vo_v_id','v_id');
    }
}
