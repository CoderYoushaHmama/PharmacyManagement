<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyModel extends Model
{
    use HasFactory;
    protected $table = 'pharmacies';
    protected $fillable = [
        'name','email','password','phone_number','address','img','wallet','facility','owner',
    ];
    public $timestamps = false;

    public function hours(){
        return $this->hasMany('App\Models\WorkingHourModel','pharmacy_id','id');
    }

    public function employees(){
        return $this->hasMany('App\Models\EmployModel','pharmacy_id','id');
    }
}
