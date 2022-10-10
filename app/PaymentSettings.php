<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentSettings extends Model
{
    use HasFactory;

    protected $table = 'payment_settings_master';

    protected $fillable = [
        'key',
        'value', 
    ];
}
