<?php

namespace App;
use Plank\Mediable\Mediable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable
{
    use HasFactory, Mediable;
    protected $table = 'clients';
    protected $fillable = ['c_name', 'c_email','c_pwd','c_status','c_fcm_token'];
    protected  $primaryKey = 'c_id';

    public function wishlist(){
        return $this->hasMany('App\user_wishlist','cw_c_id','c_id');
    }

    protected $hidden = [
        'c_pwd'
    ];
}
