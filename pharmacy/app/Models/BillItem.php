<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillItem extends Model
{
    use HasFactory;
    protected $table = 'bitems';
    protected $fillable = [
        'content_id','bill_id','quantity','b_total',
    ];
    public $timestamps = false;
}
