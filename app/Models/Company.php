<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $primaryKey = "company_id";
    public $timestamps = false;

    protected $fillable = [
        'company_name',
        'company_Email',
        'company_Phone',
        'company_description',
        'company_address',
        'company_createby',
        'company_createdate',
        'company_status'
    ];
}
