<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $table = 'coupons';
    protected $fillable = [
        'cm_title',
        'cm_code',
        'cm_description',
        'cm_type',
        'cm_date',
        'cm_start_date',
        'cm_expiry_date',
        'cm_amount',
        'cm_discount_type',
        'cm_usage_count',
        'cm_usage_limit',
        'cm_free_shipping',
        'cm_minimum_amount',
        'cm_maximum_amount',
        'cm_status'
    ];
    protected  $primaryKey = 'cm_id';
}
