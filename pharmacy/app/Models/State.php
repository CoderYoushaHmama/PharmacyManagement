<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;
    protected $table = 'states';
    protected $fillable = [
        'state_name',
    ];
    public $timestamps = false;

    public function location(){
        return $this->hasMany('App\Models\Location','state_id','id');
    }
}
