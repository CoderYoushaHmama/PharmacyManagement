<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cdelivery extends Model
{
    use HasFactory;
    protected $table = 'cdelivery';
    protected $fillable = [
        'location_id','cdelivery_address','cdelivery_name','cdelivery_phone',
        'cdelivery_line_phone','cdelivery_email',
    ];
    public $timestamps = false;
}
