<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Add this property
    protected $table = 'payments';

    // If primary key is not "id", specify it
    protected $primaryKey = 'payment_id'; // usually payment_plan_id
    public $timestamps = false;

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
}
