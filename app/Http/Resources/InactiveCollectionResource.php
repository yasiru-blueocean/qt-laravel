<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InactiveCollectionResource extends JsonResource
{
     

    public function toArray(Request $request): array
    {
        $sellingPrice = $this->selling_price ?? 0;
        $totalPaid = $this->getTotalPaid();
        $balance = $this->getBalance($sellingPrice, $totalPaid);

        return [
             'sale_id' => $this->sale_id,
            'project_name' => $this->project->project_name ?? '',
            'unit_name' => $this->unit->unit_Name ?? '',
            'customer_name' => $this->customer->C_namewinitials ?? '',
            'selling_price' => number_format($sellingPrice, 2),
            'paid_amount' => number_format($totalPaid, 2),
            'balance' => number_format($balance , 2),
            'active_status' => $this->active_status,
             'handling_person' => ($this->handlingPerson->U_FName ?? '') . ' ' . ($this->handlingPerson->U_LName ?? ''),

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
