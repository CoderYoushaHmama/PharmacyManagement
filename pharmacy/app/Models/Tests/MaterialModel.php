<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialModel extends Model
{
    use HasFactory;
    protected $table = 'materials';
    protected $fillable = [
        'company_id','material_name','scentific_name','price_of_sales','price',
        'production_date','expir_date','quantity','img',
    ];
    public $timestamps = false;

    public function company(){
        return $this->belongsTo('App\Models\CompanyModel','company_id','id');
    }

    public function treatments(){
        return $this->belongsToMany('App\Models\TreatmentModel','treatment_materials','material_id','id');
    }

    public function installations(){
        return $this->belongsToMany('App\Models\MedicalInstallationModel','medical_installation_materials','material_id','id');
    }

    public function suppliers(){
        return $this->belongsToMany('App\Models\SupplierModel','material_supplier','material_id','id');
    }
}
