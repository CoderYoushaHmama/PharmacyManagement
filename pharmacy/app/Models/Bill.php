<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $table = 'bills';
    protected $fillable = [
        'pharmacy_id','employee_id','client_id','doctor_id','date','bill_number','bill_total','bitem_number',
        'is_delivery','delivery_cost','is_return','client_name','cdelivery_id',
    ];
    public $timestamps = false;

    public function content(){
        return $this->belongsToMany('App\Models\Content','bitems','bill_id');
    }

    public function item(){
        return $this->hasMany('App\Models\BillItem','bill_id','id');
    }
}
