<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetProductsTaxResource extends JsonResource
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
            'product_id' => $this->product_id,
            'tax_id' => ucwords($this->tax_id),
            'tax_name' => ucwords($this->tax_name),
            'tax_abbreviation' => strtoupper($this->tax_abbreviation),
            'tax_rate' => number_format($this->tax_rate, 2),
        ];
    }
}
