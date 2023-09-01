<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkingHourModel extends Model
{
    use HasFactory;
    protected $table = 'working_hours';
    protected $fillable = [
        'pharmacy_id','employ_id','day','open_at','close_at',
    ];
    public $timestamps = false;
}
