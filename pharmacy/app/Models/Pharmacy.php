<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class Pharmacy extends Authenticatable implements JWTSubject
{
    use HasFactory ,HasApiTokens;
    protected $table = 'pharmacies';
    protected $fillable = [
        'location_id','pharmacy_name','email','password',
        'pharmacy_phone','pharmacy_image','pharmacy_wallet','no_facility','pharmacy_owner',
        'pharmacy_address','line_phone','is_active','no_image',
    ];
    public $timestamps = false;

    public function order(){
        return $this->hasMany('App\Models\Order','pharmacy_id','id');
    }

    public function material(){
        return $this->belongsToMany('App\Models\Material','contents','pharmacy_id');
    }

    public function content(){
        return $this->hasMany('App\Models\Content','pharmacy_id','id');
    }

    public function bill(){
        return $this->hasMany('App\Models\Bill','pharmacy_id','id');
    }

    public function waste_material(){
        return $this->belongsToMany('App\Models\Material','wastes','pharmacy_id');
    }

    public function waste(){
        return $this->hasMany('App\Models\Waste','pharmacy_id','id');
    }

    public function message(){
        return $this->hasMany('App\Models\Message','pharmacy_id','id');
    }

    public function detail(){
        return $this->hasMany('App\Models\Detail','pharmacy_id','id');
    }

    public function employee(){
        return $this->hasMany('App\Models\Employee','pharmacy_id','id');
    }

    public function location(){
        return $this->belongsTo('App\Models\Location','location_id','id');
    }

    public function material_require(){
        return $this->belongsToMany('App\Models\Material','requires','pharmacy_id');
    }

    public function stock(){
        return $this->hasMany('App\Models\Stock','pharmacy_id','id');
    }

    public function client(){
        return $this->belongsToMany('App\Models\Client','corders','pharmacy_id');
    }

    public function corder(){
        return $this->hasMany('App\Models\Corder','pharmacy_id','id');
    }

    public function batch(){
        return $this->belongsToMany('App\Models\Batch','contents','pharmacy_id');
    }

    public function require(){
        return $this->hasMany('App\Models\RequireModel','pharmacy_id','id');
    }

    public function employee_require(){
        return $this->belongsToMany('App\Models\Employee','requires','pharmacy_id');
    }

    public function rate(){
        return $this->hasMany('App\Models\Rate','pharmacy_id','id');
    }

    public function favorite(){
        return $this->hasMany('App\Models\Favorite','pharmacy_id','id');
    }

    public function favorite_material(){
        return $this->belongsToMany('App\Models\Material','favorites','pharmacy_id');
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
