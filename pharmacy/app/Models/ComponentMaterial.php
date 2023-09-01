<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComponentMaterial extends Model
{
    use HasFactory;
    protected $table = 'components_materials';
    protected $fillable = [
        'material_id','component_id','quantity_used',	
    ];
    public $timestamps = false;
}
