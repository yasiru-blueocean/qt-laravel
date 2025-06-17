<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'user';              
    protected $primaryKey = 'U_id';         
    public $timestamps = false;             

    protected $fillable = [
        'U_Title',
        'U_FName',
        'U_LName',
        'user_name',
        'U_Email',
        'U_Contact',
        'U_Designation',
        'U_Type',
        'U_Password',
        'U_Status',
        'U_Cratedby',
        'U_CratedDate',
        'u_Image',
        'pw_status'
    ];
}
