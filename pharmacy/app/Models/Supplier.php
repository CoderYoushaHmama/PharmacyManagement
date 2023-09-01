<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'suppliers';
    protected $fillable = [
        'location_id','company_id','supplier_name','supplier_image','supplier_phone','supplier_description',
        'national_number','supplier_address',
    ];
    public $timestamps = false;

    public function order(){
        return $this->hasMany('App\Models\Order','supplier_id','id');
    }
}
