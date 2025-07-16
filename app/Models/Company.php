<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
   protected $table = 'companies';
   protected $primaryKey = 'company_id';
   public $timeStamps = 'false';

   protected $fillable = [
         'company_id',
         'company_name',
         'company_Email',
         'company_Phone',
         'company_description',
         'company_address',
         'company_createby',
         'company_createdate',
         'company_status'
   ];

   
   // get the user who created the company
   public function CompanyCreateBy()
   {
     return $this ->belongsto(User::class, 'company_createby', 'U_id');
   }
}
