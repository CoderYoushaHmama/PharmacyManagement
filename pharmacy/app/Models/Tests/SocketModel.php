<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocketModel extends Model
{
    use HasFactory;
    protected $table = 'sockets';
    protected $fillable = [
        'pharmacy_id','avaliable_quantity','purchase_price','selling_price',
    ];
    public $timestamps = false;
}
