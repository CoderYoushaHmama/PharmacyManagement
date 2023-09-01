<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployModel extends Model
{
    use HasFactory;
    protected $table = 'employ';
    protected $fillable = [
        'pharmacy_id','name','email','password','address','phone_number',
    ];
    public $timestamps = false;
}
