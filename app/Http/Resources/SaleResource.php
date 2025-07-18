<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Payment;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     * 
     */   

    public function toArray(Request $request): array
    {

        $sellingPrice = $this->selling_price ?? 0;
        $totalPaid = $this->getTotalPaid();
        $balance = $this->getBalance($sellingPrice, $totalPaid);
        $dueAmount = $this->getDueAmount();


        return [
            'sale_id' => $this->sale_id,
            'sale_date' => $this->sale_date,
            'project'=>[
                'project_name' => $this->project->project_name ?? '',
            ],
            'unit' => [
                'unit_Name' => $this->unit->unit_Name ?? '',
            ],
            'customer' => [
                'C_namewinitials' => $this->customer->C_namewinitials ?? '',
            ],
            'selling_price' => number_format($sellingPrice, 2),
            'paid_amount' => number_format($totalPaid, 2),
            'balance' => number_format($balance , 2),
            'due_amount' => number_format($dueAmount, 2),
            'active_status' => $this->active_status,
            'has_payment_plan_data' => $this->getPaymentPlanFlag()

        ];
    }

    //Calculate the total paid amount for the sale
    private function getTotalPaid()
    {
        return $this-> payments()
        ->where('active_status', 0)
        -> sum('paid_amount');
    }

    //Calculate the balance of the sale
    private function getBalance($sellingPrice, $totalPaid)
    {
        return $sellingPrice - $totalPaid;
    }

    //calculate the sum of overdue amount of the sale
    private function getDueAmount()
    {
        $plans = $this->paymentPlans()
        ->whereRaw("COALESCE (actual_due_date, due_date) < CURDATE()")
        ->get();

        $totalDue = 0;

        foreach ($plans as $plan)
        {
            $paid = Payment::where('sale_id', $this->sale_id)
            ->where('pay_discription', $plan->installment)
            ->where ('active_status', 0)
            ->sum('paid_amount');

            $unpaid = $plan->amount - $paid;

            if ($unpaid > 0)
            {
                $totalDue += $unpaid;
            }
        }
        return $totalDue;

    }


     /**
     * Flag for payment plan status:
     * - 2: No plans
     * - 1: Only a single reservation fee installment exists
     * - 0: Has an actual payment plan
     */

   private function getPaymentPlanFlag()
   {
    $plans = $this->paymentPlans;
    if($plans->count() === 0) return 2;
    if($plans->count() === 1 && $plans[0]->installment === 'Reservation Fee') return 1;
    return 0;
   }
}
