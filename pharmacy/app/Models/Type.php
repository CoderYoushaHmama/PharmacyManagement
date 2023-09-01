<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table = 'types';
    protected $fillable = [
        'type_name',
    ];
    public $timestamps = false;

    public function material(){
        return $this->hasMany('App\Models\Material','type_id','id');
    }
}
