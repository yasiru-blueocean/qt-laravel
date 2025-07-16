<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'company_id' => $this->company_id,
            'company_name' => $this->company_name,
            // 'company_Email' => $this->company_Email,
            // 'company_Phone' => $this->company_Phone,
            'company_description' => $this->company_description,
            'company_address' => $this->company_address,
            'CompanyCreateBy' => [
                'U_FName' => $this->CompanyCreateBy->U_FName ?? '',
                'U_LName' => $this->CompanyCreateBy->U_LName ?? '',
                'u_Image' => $this->CompanyCreateBy->u_Image ?? null,
            ],
            'company_createdate' => $this->company_createdate,
            'company_status' => $this->company_status,

        ];
    }
}
