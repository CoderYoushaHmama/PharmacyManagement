<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $table = 'materials';
    protected $fillable = [
        'company_id','type_id','material_name','scientific_name','material_image','pp','ps','qr_code',
        'license_image','is_active',
    ];
    public $timestamps = false;

    public function disease(){
        return $this->belongsToMany('App\Models\Disease','diseases_material','material_id');
    }

    public function component(){
        return $this->belongsToMany('App\Models\Component','components_materials','material_id');
    }

    public function component_info(){
        return $this->hasMany('App\Models\ComponentMaterial','material_id','id');
    }

    public function bill(){
        return $this->belongsToMany('App\Models\Bill','bitems','material_id');
    }

    public function order(){
        return $this->belongsToMany('App\Models\Order','oitems','material_id');
    }

    public function pharmacy_contents(){
        return $this->belongsToMany('App\Models\Pharmacy','contents','material_id');
    }

    public function pharmacy_waste(){
        return $this->belongsToMany('App\Models\Pharmacy','wastes','material_id');
    }

    public function batch(){
        return $this->hasMany('App\Models\Batch','material_id','id');
    }

    public function pharmacy_require(){
        return $this->hasMany('App\Models\RequireModel','material_id','id');
    }

    public function pharmacy_employee(){
        return $this->belongsToMany('App\Models\Employee','requires','material_id');
    }
    
    public function corder(){
        return $this->belongsToMany('App\Models\Corder','icorders','material_id');
    }

    public function content(){
        return $this->hasMany('App\Models\Content','material_id','id');
    }
}
