<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentModel extends Model
{
    use HasFactory;
    protected $table = 'tratment';
    protected $fillable = [
        'treatment',
    ];
    public $timestamps = false;

    public function materials(){
        return $this->belongsToMany('App\Models\MaterialModel','treatment_materials','treatment_id','id');
    }
}
