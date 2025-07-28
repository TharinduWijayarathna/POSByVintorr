<?php

namespace App\Http\Resources;

use App\Models\PosOrder;
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
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'email' => $this->email,
            'email2' => $this->email2,
            'email3' => $this->email3,
            'contact' => $this->contact,
            'contact2' => $this->contact2,
            'contact3' => $this->contact3,
            'status' => $this->status,
            'company' => $this->company,
            'customer_due' => $this->CustomerDue(),
        ];
    }

    /**
     * Calculate the customer's due amount.
     *
     * @return string
     */
    public function CustomerDue(): string
    {
        $orders = PosOrder::where('customer_id', $this->id)
            ->where('credit_status', 0)
            ->whereNotIn('status', [2, 4, 5])
            ->get();

        $groups = [];

        foreach ($orders as $order) {
            $currencyId = $order->currency_id;

            if (!isset($groups[$currencyId])) {
                $groups[$currencyId] = [
                    'currency_name' => $order->currency_name,
                    'due_amount' => 0,
                ];
            }

            $groups[$currencyId]['due_amount'] += $order->total - $order->customer_paid;
        }

        $result = array_map(function ($group) {
            return "{$group['currency_name']} " . number_format($group['due_amount'], 2);
        }, array_values($groups));

        return implode(', ', $result);
    }
}
