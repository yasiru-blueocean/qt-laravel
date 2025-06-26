<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $primaryKey = 'sale_id';

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function unit()
    {
        return $this->belongsTo(ProjectUnit::class, 'unit_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'Customer_id');
    }

    public function paymentPlans()
    {
        return $this->hasMany(PaymentPlan::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_id')->where('active_status' , 0);
    }

   
    //Accessor for get total paid amount
    public function getTotalPaidAttribute()
    {
        return $this->payments->sum('paid_amount');
    }

    //Accessor for get balance 
    public function getBalanceAttribute()
    {
        return ($this->selling_price ?? 0) - $this->total_paid;
    }

    //Accessor for get due amount

    public function getDueAmountAttribute()
    {
        return $this->paymentPlans
            ->filter(function ($plan) {
                $dueDate = $plan->actual_due_date ?? $plan->due_date;
                return $dueDate < now()->toDateString() &&
                    ($plan->amount - $plan->paid_amount_for_plan) > 0;
            })
            ->sum(function ($plan) {
                return $plan->amount - $plan->paid_amount_for_plan;
            });
    }

    //Accessor for get payment plan status data
    public function getHasPaymentPlanDataAttribute()
    {
        $count = $this->paymentPlans->count();
        if ($count > 1) return 0;
        if ($count == 1) {
            return $this->paymentPlans->first()->installment === "Reservation Fee" ? 1 : 0;
        }
        return 2;
    }

      public function user()
    {
        return $this->belongsTo(User::class, 'sale_by', 'U_id');
    }
    }
