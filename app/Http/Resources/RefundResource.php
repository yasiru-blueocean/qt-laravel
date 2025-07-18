<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Sale;

class RefundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        $total_paid_amount = $this->total_paid_amount ?? 0;
        $deduction_amount = $this->deduction_amount ?? 0;
        $refund_amount = $this->getRefundAmount($total_paid_amount, $deduction_amount);

        return [
            'refund_id' => $this->refund_id,
            'project' => [
                'project_name' => $this->sale->project->project_name ?? '',
            ],
            'unit' => [
                'unit_Name' => $this->sale->unit->unit_Name ?? '',
            ],
            'customer' => [
                'C_namewinitials' => $this->sale->customer->C_namewinitials ?? ''
            ],
            'total_paid_amount' => number_format($this-> total_paid_amount,2),
            'deduction_amount' => number_format($this-> deduction_amount , 2),
            'refund_amount' => number_format($refund_amount,2),
            'has_payment_plan' => $this->getRefundPaymentPlanFlag(),
        ];
    }

        public function getRefundAmount($total_paid_amount,$deduction_amount )
        {
            return $total_paid_amount - $deduction_amount;
        }

        public function getRefundPaymentPlanFlag()
        {
            return $this->refundpaymentPlans()->exists();
        }
}
