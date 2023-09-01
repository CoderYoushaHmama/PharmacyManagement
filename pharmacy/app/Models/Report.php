<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';   
    protected $fillable = [
        'is_require','is_order','is_bill','is_corder','is_waste','company_id','pharmacy_id','employee_id',
        'client_id','doctor_id','from','to','total','date',
    ];
    public $timestamps = false;
}
