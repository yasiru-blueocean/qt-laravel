<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       return [
        'Customer_id' => $this->Customer_id,
        'C_Title' => $this->C_Title,
        'C_fullname' => $this->C_fullname,
        'C_namewinitials' => $this->C_namewinitials,
        'C_Occupation' => $this->C_Occupation,
        'C_NIC' => $this->C_NIC,
        'C_Passport' => $this->C_Passport,
        // 'C_berth' => $this->C_berth,
        // 'C_Gender' => $this->C_Gender,
        // 'C_Address' => $this->C_Address,
        'C_Country' => $this->C_Country,
        // 'C_Phone' => $this->C_Phone,
        'C_Email' => $this->C_Email,
        'C_status' => $this->C_status,
        // 'C_CompanyName' => $this->C_CompanyName,
        // 'C_AddressCS' => $this->C_AddressCS,
        // 'C_Cratedby' => $this->C_Cratedby,
        // 'C_CratedDate' => $this->C_CratedDate,
        'BR_Number' =>$this->BR_Number,
     ];
    }
}
