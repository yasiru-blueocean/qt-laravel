<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens;

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

    protected $hidden = ['U_Password']; // hide password 
}