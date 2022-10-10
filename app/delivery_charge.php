<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class delivery_charge extends Model
{
    use HasFactory;
    protected $table = 'delivery_charges';
    protected $fillable = ['dc_postcode', 'dc_price', 'dc_is_default'];
    protected  $primaryKey = 'dc_id';
}
