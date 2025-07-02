<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// PaymentPlan.php
namespace App\Models;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class PaymentPlan extends Model
{
    protected $table = 'payment_plan';

    // Relationship: payments for this plan
    public function sale()
    {
        return $this->belongsTo(Sale::class , 'plan_id');
    }

    public function getPaidAmountForPlanAttribute()
    {
        return Payment::where('sale_id', $this->sale_id)
            ->where('pay_discription', $this->installment)
            ->where('active_status', 0)
            ->sum('paid_amount');
    }


    public function payments()
    {
        return $this->hasMany(Payment::class, 'sale_id', 'sale_id')
            ->whereColumn('pay_discription', 'installment')
            ->where('active_status', 0);
    }


    public function getDueAmountAttribute()
    {
        // Match payments that belong to the same sale and the same installment name
        $paid = Payment::where('sale_id', $this->sale_id)
            ->where('pay_discription', $this->installment)
            ->where('active_status', 0)
            ->sum('paid_amount');

        return max($this->amount - $paid, 0);
    }


}

