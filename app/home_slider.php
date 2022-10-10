<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class home_slider extends Model
{
    use HasFactory;
    protected $table = 'home_sliders';
    protected $fillable = [
        'hs_image',
        'hs_order',
    ];
    protected  $primaryKey = 'hs_id';
}
