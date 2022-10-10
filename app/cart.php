<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cart extends Model
{
    use HasFactory;
    protected $fillables = [
        'p_id',
        'taken_qty',
        'user_id'
    ];
    public function product(){
        return $this->belongsTo(Product::class,'p_id');
    }
    protected  $primaryKey = 'cart_id';
}
