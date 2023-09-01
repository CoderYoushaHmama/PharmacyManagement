<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'employees';
    protected $fillable = [
        'pharmacy_id','employee_name','email','password',
        'employee_address','employee_phone','salary','wallet',
    ];
    public $timestamps = false;

    public function bill(){
        return $this->hasMany('App\Models\Bill','employee_id','id');
    }

    public function message(){
        return $this->hasMany('App\Models\Message','employee_id','id');
    }

    public function waste(){
        return $this->hasMany('App\Models\Waste','employee_id','id');
    }

    public function requires(){
        return $this->hasMany('App\Models\RequireModel','employee_id','id');
    }

    public function material(){
        return $this->belongsToMany('App\Models\Material','requires','employee_id');
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
