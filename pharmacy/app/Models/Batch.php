<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;
    protected $table = 'batchs';
    protected $fillable = [
        'material_id','batch','quantity','expiry_date','description','is_active',
    ];
    public $timestamps = false;

    public function order(){
        return $this->belongsToMany('App\Models\Order','oitems','batchs_id','id');
    }

    public function content(){
        return $this->belongsToMany('App\Models\Pharmacy','contents','batch_id');
    }
    public function contents(){
        return $this->hasMany('App\Models\Contnet','batch_id','id');
    }
}
