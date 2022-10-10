<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class smtp_setting extends Model
{
    protected $table = 'smtp_setting';

    protected $fillable = [
        'ss_mailer',
        'ss_host', 
        'ss_port',
        'ss_uname',
        'ss_pwd',
        'ss_encryption',
        'ss_from_address',
        'ss_from_name'
    ];
    
    protected  $primaryKey = 'ss_id';
}
