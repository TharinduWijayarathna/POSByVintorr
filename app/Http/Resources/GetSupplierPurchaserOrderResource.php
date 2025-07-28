<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSupplierPurchaserOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cashier_name' => $this->cashier_name,
            'code' => $this->code,
            'date' => $this->date,
            'currency_name' => $this->currency->code,
            'formatted_total' => $this->formatted_total,
        ];
    }
}
