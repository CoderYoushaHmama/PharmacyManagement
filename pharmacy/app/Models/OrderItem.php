<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'oitems';
    protected $fillable = [
        'order_id','material_id','quantity','total_price','batch_id',
    ];
    public $timestamps = false;
}
