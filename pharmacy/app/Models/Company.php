<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Company extends Authenticatable implements JWTSubject
{
    use HasFactory;
    protected $table = 'companies';
    protected $fillable = [
        'location_id','company_name','company_phone','email','password',
        'company_owner','establishment_no','company_image',	'es_image','is_active','line_phone',
        'company_address',
    ];
    public $timestamps = false;

    public function material(){
        return $this->hasMany('App\Models\Material','company_id','id');
    } 

    public function supplier(){
        return $this->hasMany('App\Models\Supplier','company_id','id');
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
