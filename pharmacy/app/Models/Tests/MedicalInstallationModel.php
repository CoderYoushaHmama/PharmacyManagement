<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalInstallationModel extends Model
{
    use HasFactory;
    protected $table = 'medical_installation';
    protected $fillable = [
        'material_id','components',
    ];
    public $timestamps = false;

    public function materials(){
        return $this->belongsToMany('App\Models\MaterialModel','medical_installation_materials','medical_installation_id','id');
    }
}
