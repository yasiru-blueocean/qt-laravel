<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
    use Illuminate\Support\Facades\DB;

class SaleViseDueCollectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $sellingPrice = $this->selling_price ?? 0;
        $totalPaid = $this->getTotalPaid();
        $balance = $this->getBalance($sellingPrice, $totalPaid);
        $dueAmount = $this->getDueAmount();

        return [
            'sale_id' => $this->sale_id,
            'sale_date' => $this->sale_date,
            'project_name' => $this->project->project_name ?? '',
            'unit_name' => $this->unit->unit_Name ?? '',
            'company_name' => $this->project->company->company_name ?? '',
            'customer_name' => $this->customer->C_namewinitials ?? '',
            'selling_price' => number_format($sellingPrice, 2),
            'paid_amount' => number_format($totalPaid, 2),
            'balance' => number_format($balance, 2),
            'handling_person' => trim(($this->handlingPerson->U_FName ?? '') . ' ' . ($this->handlingPerson->U_LName ?? '')),
            'active_status' => $this->active_status,
            'due_amount' => number_format($dueAmount, 2),
        ];
    }

    private function getTotalPaid(): float
    {
        return $this->payments
            ->where('active_status', 0)
            ->sum('paid_amount');
    }

    private function getBalance(float $sellingPrice, float $totalPaid): float
    {
        return $sellingPrice - $totalPaid;
    }


    private function getDueAmount(): float
    {
        $dueAmount = DB::table('payment_plan as pp')
        ->select(DB::raw('SUM(pp.amount - COALESCE((
            SELECT SUM(paid_amount)
            FROM payments
            WHERE sale_id = pp.sale_id 
            AND pay_discription = pp.installment
            AND active_status = 0
        ), 0)) AS due_amount'))
        ->where('pp.sale_id', $this->sale_id)
        ->whereRaw('COALESCE(pp.actual_due_date, pp.due_date) < CURDATE()')
        ->whereRaw('(pp.amount - COALESCE((
            SELECT SUM(paid_amount)
            FROM payments
            WHERE sale_id = pp.sale_id
            AND pay_discription = pp.installment
            AND active_status = 0
        ), 0)) > 0')
        ->value('due_amount');

    return (float) ($dueAmount ?? 0);

}


}
