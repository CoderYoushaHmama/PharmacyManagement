<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';
    protected $fillable = [
        'state_id','location_name',
    ];
    public $timestamps = false;

    public function company(){
        return $this->hasMany('App\Models\Company','location_id','id');
    }

    public function supplier(){
        return $this->hasMany('App\Models\Supplier','location_id','id');
    }

    public function client(){
        return $this->hasMany('App\Models\Client','location_id','id');
    }

    public function pharmacy(){
        return $this->hasMany('App\Models\Pharmacy','location_id','id');
    }

    public function state(){
        return $this->belongsTo('App\Models\State','state_id','id');
    }

    public function stocks(){
        return $this->hasMany('App\Models\Stock','location_id','id');
    }
}
