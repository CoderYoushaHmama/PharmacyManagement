<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Client extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'clients';
    protected $fillable = [
        'location_id','client_name','email','password','client_phone','client_description',
    ];
    public $timestamps = false;

    public function bill_pharmacy(){
        return $this->hasMany('App\Models\Bill','client_id');
    }

    public function corder(){
        return $this->hasMany('App\Models\Corder','client_id','id');
    }

    public function pharmacy(){
        return $this->belongsToMany('App\Models\Pharmacy','corders','client_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }
}