<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $table = 'refund';
    protected $primaryKey = 'refund_id';
    public $timeStamps = 'false';

    protected $fillable = [
        'refund_id',
        'sale_id',
        'total_paid_amount',
        'active_status',
        'deduction_amount'
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id' , 'sale_id');
    }

    public function refundPaymentPlans()
    {
        return $this->hasMany(RefundPaymentPlan::class, 'refund_id' , 'refund_id');
    }
}
