<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SocialAuth extends Model
{
    //
    protected $table = 'social_master';

    protected $fillable = [
        'key',
        'value', 
    ];
}
