<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalInstallationMaterialModel extends Model
{
    use HasFactory;
    protected $table = 'medical_installation_materials';
    protected $fillable = [
        'material_id','medical_installation_id',
    ];
    public $timestamps = false;
}
