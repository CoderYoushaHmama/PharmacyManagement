<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'orders';
    protected $fillable = [
        'pharmacy_id','company_id','supplier_id','date','order_number','order_total',
        'order_description','is_accept','items_count','mes_company','send_date','is_sent',
    ];
    public $timestamps = false;

    public function material(){
        return $this->belongsToMany('App\Models\Material','oitems','order_id');
    }

    public function order_item(){
        return $this->hasMany('App\Models\OrderItem','order_id','id');
    }
}