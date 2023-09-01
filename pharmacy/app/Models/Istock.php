<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Istock extends Model
{
    use HasFactory;
    protected $table = 'istocks';
    protected $fillable = [
        'content_id','stock_id','quantity',
    ];
    public $timestamps = false;

    public function content(){
        return $this->belongsTo('App\Models\Content','content_id','id');
    }
}
