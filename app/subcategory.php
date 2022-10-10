<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subcategory extends Model
{
    use HasFactory;
    protected $fillables = [
       'id',
       'cid',
       'Product_category_name'
    ];
    public function category(){
        return $this->belongsTo(Category::class,'cid');
    }
}
