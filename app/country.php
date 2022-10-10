<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class country extends Model
{
    use HasFactory;

    // protected $table = 'delivery_charges';
   protected $fillable = ['country_id', 'country_code', 'country_name'];
    protected  $primaryKey = 'country_id';
}
