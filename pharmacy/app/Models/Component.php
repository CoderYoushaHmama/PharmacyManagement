<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;
    protected $table = 'components';
    protected $fillable = [
        'component_name',
    ];
    public $timestamps = false;

    public function material(){
        return $this->belongsToMany('App\Models\Material','components_materials','component_id');
    }
    
    public function supplier(){
        return $this->hasMany('App\Models\Supplier','company_id','id');
    }

    public function order(){
        return $this->hasMany('App\Models\Order','company_id','id');
    }
}