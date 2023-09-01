<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;
    protected $table = 'details';
    protected $fillable = [
        'pharmacy_id','day','open','close','is_duty',
    ];
    public $timestamps = false;
}
