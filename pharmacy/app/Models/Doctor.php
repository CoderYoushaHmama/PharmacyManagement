<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'doctors';
    protected $fillable = [
        'location_id','doctor_address','doctor_name','email','password','doctor_phone',
        'doctor_description','doctor_image','d_image','is_accept',
    ];
    public $timestamps = false;

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