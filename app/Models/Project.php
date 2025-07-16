<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'project';
    protected $primaryKey = 'project_id';
    public $timeStamps = 'false';

    protected $fillable = [
        'project_id',
        'project_name',
        'project_location',
        'project_description',
        'project_status',
        'project_createby',
        'project_createdate',
        'company_id'
    ];

    public function projectCreateBy()
    {
        return $this->belongsTo(User::class, 'project_createby', 'U_id' );
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id' , 'company_id');
    }

}
