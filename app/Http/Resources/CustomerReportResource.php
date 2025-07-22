<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerReportResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'customer_id' => $this->Customer_id,
            'C_Title' => $this->C_Title,
            'C_namewinitials' => $this->C_namewinitials,
            'C_NIC' => $this->C_NIC,
            'purchase_ids' => $this->sales->pluck('sale_id')->unique()->values(),
            'f_name_array' => $this->sales->pluck('saleBy.U_FName')->values(),
            'project_names' => $this->sales->pluck('project.project_name')->values(),
            'unit_names' => $this->sales->pluck('unit.unit_Name')->values(),
            'company_names' => $this->sales->pluck('project.company.company_name')->values(),
        ];
    }
}
