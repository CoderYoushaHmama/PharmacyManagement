<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icorder extends Model
{
    use HasFactory;
    protected $table = 'icorders';
    protected $fillable = [
        'material_id','corder_id','quantity','total_price',
    ];
    public $timestamps = false;
}
