<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUnits extends Model
{
    protected $table = 'project_units';
    protected $primaryKey = 'unit_id';
    public $timeStamps = 'false';

    protected $fillable = [
        'unit_id',
        'unit_Name',
        'unit_Description',
        'unit_Size',
        'unit_Price',
        'company_id',
        'project_id',
        'unit_status',
        'unit_createby',
        'unit_createdate',
        'Unit_delete'
  
    ];
}
