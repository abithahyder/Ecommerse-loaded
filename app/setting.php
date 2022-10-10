<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class setting extends Model
{
    protected $table = 'settings';
    protected $fillable = ['currency', 'maintenance_mode', 'notification_day'];
}
