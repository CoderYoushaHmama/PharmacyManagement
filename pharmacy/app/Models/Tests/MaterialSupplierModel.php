<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialSupplierModel extends Model
{
    use HasFactory;
    protected $table = 'material_supplier';
    protected $fillable = [
        'supplier_id','material_id','amount_of_materials',
    ];
    public $timestamps = false;
}
