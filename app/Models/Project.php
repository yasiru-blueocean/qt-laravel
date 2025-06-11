<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';
    protected $primaryKey = "project_id";
    public $timestamps = false;

    protected $fillable = [
        'project_name',
        'project_location',
        'project_description',
        'project_status',
        'project_createby',
        'project_createdate',
        'company_id'
    ];
}
