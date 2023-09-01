<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequireModel extends Model
{
    use HasFactory;
    protected $table = 'requires';
    protected $fillable = [
        'material_id','pharmacy_id','employee_id','date','quantity','description','require_number','is_accept',
    ];
    public $timestamps = false;
}
