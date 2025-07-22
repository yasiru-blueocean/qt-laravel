<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customer';
    protected $primaryKey = 'Customer_id';
    public $timestamps = false;

    protected $fillable = [
         'C_Title',
         'C_fullname',
         'C_namewinitials',
         'C_Occupation',
         'C_NIC',
         'C_Passport',
         'C_berth',
         'C_Gender',
         'C_Address',
         'C_AddressP',
         'C_Country',
         'C_Phone',
         'C_LandNo',
         'C_Email',
         'C_CompanyName',
         'C_AddressCS',
         'C_CratedBy',
         'C_CratedDate',
         'C_status',
         'BR_Number'
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class, 'Customer_id', 'Customer_id');
    }
}
