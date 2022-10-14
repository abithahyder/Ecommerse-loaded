<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    use HasFactory;
    protected $table = 'client_addresses';
    protected $fillable = ['ca_c_id', 'ca_address_line_1','ca_address_line_2','ca_mobile','ca_city','ca_state','ca_country','ca_pincode'];
    protected  $primaryKey = 'ca_id';

    protected $hidden = [
        'ca_c_id'
    ];

}
