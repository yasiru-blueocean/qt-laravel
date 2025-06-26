<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Refund;

class Refund extends Model
{
    protected $primaryKey = 'refund_id';
    protected $table = 'refund';

    protected $appends = ['refund_amount', 'has_payment_plan'];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    // Accessor: Refund Amount = Total Paid - Deduction
    public function getRefundAmountAttribute()
    {
        return ($this->total_paid_amount ?? 0) - ($this->deduction_amount ?? 0);
    }

     public function refundpaymentPlans()
    {
        return $this->hasMany(RefundPaymentPlan::class, 'refund_id');
    }
    // Accessor: Has Payment Plan (true/false)
     public function getHasPaymentPlanAttribute()
    {
        return $this->refundpaymentPlans()->exists();
    }


   

}

