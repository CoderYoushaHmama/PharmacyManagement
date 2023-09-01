<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Corder extends Model
{
    use HasFactory;
    protected $table = 'corders';
    protected $fillable = [
        'pharmacy_id','client_id','doctor_id','corder_number','date','corder_total','coitem_number',
        'co_description','is_delivery','address','is_accept','mes_pharmacy',
    ];
    
    public $timestamps = false;

    public function material(){
        return $this->belongsToMany('App\Models\Material','icorders','corder_id');
    }

    public function icorder(){
        return $this->hasMany('App\Models\Icorder','corder_id','id');
    }
}
