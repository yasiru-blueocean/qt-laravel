<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'U_id' => $this->U_id,
            'fullname' => $this->U_Title . ' ' . $this->U_FName . ' ' . $this->U_LName, 
            'U_Title' => $this->U_Title,
            'U_FName' => $this->U_FName,
            'U_LName' => $this->U_LName,
            // 'user_name' => $this->user_name, 
            'U_Email' => $this->U_Email,
            'U_Contact' => $this->U_Contact,
            'U_Designation' => $this->U_Designation,
            'U_Type' => $this->U_Type,
            'U_Status' => $this->U_Status,
            'U_Cratedby' => $this->U_Cratedby,
            'U_CratedDate' => $this->U_CratedDate,
            'U_Password' => $this->U_Password,
            'u_Image' => $this->u_Image,
            'pw_status' => $this->pw_status,
        ];
    }
}
