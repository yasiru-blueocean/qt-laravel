<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
   protected $table = 'sales';
   protected $primaryKey = 'sale_id';
   public $timeStamps = 'false';

   protected $fillable = [
       'Customer_id',
       'Customer_idS',
       'project_id',
       'unit_id',
       'sale_date',
       'sale_unit_price',
       'sale_unit_discount_price',
       'sale_unit_discount_price_presentage',
       'selling_price',
       'sale_crate_bate',
       'sale_by',
       'sale_crate_by',
       'active_status',
       'handling_person',
       'installment_status'

   ];

   public function Customer()
   {
    return $this->belongsTo(Customer::class, 'Customer_id', 'Customer_id');
   }

//    public function CustomerSecondary()
//    {
//     return $this->belongsTo(Customer::class, 'Customer_idS', 'Customer_id');
//    }

   public function project()
   {
    return $this->belongsTo(Project::class, 'project_id', 'project_id');
   }

   public function unit()
   {
    return $this->belongsTo(ProjectUnits::class, 'unit_id', 'unit_id');
   }

   public function saleBy()
   {
    return $this->belongsTo(User::class, 'sale_by', 'U_id');
   }

//    public function sale_crate_by()
//    {
//     return $this->belongsTo(User::class, 'sale_crate_by', 'U_id');
//    }

   public function handlingPerson()
   {
    return $this->belongsTo(User::class, 'handling_person', 'U_id');
   }

   public function payments()
   {
    return $this->hasMany(Payment::class, 'sale_id' , 'sale_id');
   }

   public function paymentPlans()
   {
    return $this->hasMany(PaymentPlan::class, 'sale_id', 'sale_id');
   }

}
