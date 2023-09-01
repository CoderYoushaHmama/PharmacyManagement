<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentMaterialModel extends Model
{
    use HasFactory;
    protected $table = 'treatment_materials';
    protected $fillable = [
        'material_id','treatment_id',
    ];
    public $timestamps = false;
}
