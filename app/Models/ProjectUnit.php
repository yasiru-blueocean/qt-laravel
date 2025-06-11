<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUnit extends Model
{
    protected $table = 'project_units';
    protected $primaryKey = 'unit_id';
    public $timestamps = false;

    protected $fillable = [
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

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'company_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id', 'project_id');
    }
}
