<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiseaseMedicine extends Model
{
    use HasFactory;
    protected $table = 'diseases_material';
    protected $fillable = [
        'material_id','disease_id',	
    ];
    public $timestamps = false;
}
