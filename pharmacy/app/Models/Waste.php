<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waste extends Model
{
    use HasFactory;
    protected $table = 'wastes';
    protected $fillable = [
        'pharmacy_id','employee_id','wastes_number','date','w_descriptions','waste_total',
        'iwaste_number',
    ];
    public $timestamps = false;

    public function content(){
        return $this->belongsToMany('App\Models\Content','witems','waste_id');
    }
    public function item(){
        return $this->hasMany('App\Models\WasteItem','waste_id','id');
    }
}
