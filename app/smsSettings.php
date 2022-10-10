<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class smsSettings extends Model
{
    use HasFactory;

    protected $table = 'sms_settings_master';

    protected $fillable = [
        'key',
        'value', 
    ];
}
