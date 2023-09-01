<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    use HasFactory;
    protected $table = 'company';
    protected $fillable = [
        'name','email','password','owner','phone_number','address','website',
        'contact_email','facility','img',
    ];
    public $timestamps = false;

    public function materials(){
        return $this->hasMany('App\Models\MaterialModel','company_id','id');
    }
}
