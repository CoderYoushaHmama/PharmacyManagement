<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;
    protected $table = 'diseases';
    protected $fillable = [
        'disease_name',
    ];
    public $timestamps = false;

    public function medicine(){
        return $this->belongsToMany('App\Models\Material','diseases_material','disease_id');
    }
}