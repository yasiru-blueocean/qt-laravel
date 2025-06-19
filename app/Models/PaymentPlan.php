<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// PaymentPlan.php
namespace App\Models;

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

}

