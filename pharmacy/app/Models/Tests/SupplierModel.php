<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'name','phone_number','address',
    ];
    public $timestamps = false;

    public function materials(){
        return $this->belongsToMany('App\Models\MaterialModel','material_supplier','supplier_id','id');
    }
}
