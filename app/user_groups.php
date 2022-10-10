<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user_groups extends Model
{   
    protected $table = 'user_groups';
    protected $fillable = ['ug_name', 'ug_created_at'];
    protected  $primaryKey = 'ug_id';
}
