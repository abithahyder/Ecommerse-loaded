<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product_review extends Model
{
    use HasFactory;
   
    protected $fillable = [
        'pr_c_id',
        'pr_p_id',
        'pr_rating',
        'pr_review',
    ];
    protected  $primaryKey = 'pr_id';

    protected  $hidden = [ 'media' ];

    public function user(){
        return $this->belongsTo('App\ClientModel','pr_c_id','c_id');
    }
}
