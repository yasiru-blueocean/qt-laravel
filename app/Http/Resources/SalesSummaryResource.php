<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SalesSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        

         $sellingPrice = $this->selling_price ?? 0;
        $totalPaid = $this->getTotalPaid();
        $balance = $this->getBalance($sellingPrice, $totalPaid);

        return [
            'sale_id' => $this->sale_id,
            'sale_date' => $this->sale_date,
            'project_name' => $this->project->project_name ?? '',
            'unit_name' => $this->unit->unit_Name ?? '',
            'company_name' => $this->project->company->company_name ?? '',
            'customer_name' => $this->customer->C_namewinitials ?? '',
            'handling_person' => ($this->handlingPerson->U_FName ?? '') . ' ' . ($this->handlingPerson->U_LName ?? ''),
            'selling_price' => number_format($sellingPrice, 2),
            'paid_amount' => number_format($totalPaid, 2),
            'balance' => number_format($balance , 2),
            'active_status' => $this->active_status
        ];
    }

    private function getTotalPaid()
    {
        return $this->payments()
        ->where('active_status', 0)
        ->sum('paid_amount');
    }

    private function getBalance($sellingPrice, $totalPaid)
    {
        return $sellingPrice - $totalPaid;
    }
}
