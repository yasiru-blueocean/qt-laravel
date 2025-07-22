<?php

// app/Http/Resources/MonthlySalesSummaryResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlySaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'year' => $this['year'],
            'month' => $this['month'],
            'sale_count' => $this['sale_count'],
            'sale_unit_price_total' => number_format($this['sale_unit_price_total'], 2),
            'discount_total' => number_format($this['discount_total'], 2),
            'selling_price_total' => number_format($this['selling_price_total'], 2),
            'selectedCompany' => $this['selected_company'] ?? 'SelectCompany',
            'selectedProject' => $this['selected_project'] ?? 'SelectProject',
            'selectedSalesMember' => $this['selected_sales_member'] ?? 'SelectSalesMember',
        ];
    }
}



