<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefundPaymentPlan extends Model
{
    protected $table = 'refund_payment_plan'; // if table name doesn't match Laravel's plural rule

    protected $primaryKey = 'id'; // or your actual primary key

    public $timestamps = false; // if your table doesn’t have created_at, updated_at

    public function refund()
    {
        return $this->belongsTo(Refund::class, 'refund_id');
    }
}
