<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;
    protected $table = 'contents';
    protected $fillable = [
        'pharmacy_id','material_id','batch_id','quantity','min','max',	
    ];
    public $timestamps = false;

    public function bill(){
        return $this->belongsToMany('App\Models\Bill','bitems','content_id');
    }

    // public function stock(){
    //     return $this->hasMany('App\Models\Stock','content_id','id');
    // }

    public function waste(){
        return $this->belongsToMany('App\Models\Waste','witems','content_id','id');
    }
    
    public function stock(){
        return $this->belongsToMany('App\Models\Stock','istocks','content_id');
    }

    public function istock(){
        return $this->hasMany('App\Models\Istock','content_id','id');
    }
}
