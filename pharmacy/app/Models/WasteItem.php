<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasteItem extends Model
{
    use HasFactory;
    protected $table = 'witems';
    protected $fillable = [
        'content_id','waste_id','quantity','total_pp',
    ];
    public $timestamps = false;
}
