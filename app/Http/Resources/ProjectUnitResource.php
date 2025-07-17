<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectUnitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'unit_id' => $this->unit_id,
            'unit_Name' => $this->unit_Name,
            'unit_Description' => $this-> unit_Description,
            'unit_Size'=> $this->unit_Size .'SQ.FT',
            'unit_Price' => $this-> unit_Price . 'LKR',
            'unit_status' => $this-> unit_status,
            'project_id' => $this-> project_id,
            'Unit_delete' => $this->Unit_delete

        ];
    }
}
