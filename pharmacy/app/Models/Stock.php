<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stocks';
    protected $fillable = [
        'pharmacy_id','location_id','stock_name','space','temp',
    ];
    public $timestamps = false;

    public function content(){
        return $this->belongsToMany('App\Models\Content','istocks','stock_id');
    }

    public function istock(){
        return $this->hasMany('App\Models\Istock','stock_id','id');
    }
}
