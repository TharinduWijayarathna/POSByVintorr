<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetBusinessDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // edit_business.id = response.id;
        // edit_business.name = response.name;
        // edit_business.address = response.address;
        // edit_business.mobile = response.mobile;
        // edit_business.email = response.email;
        // edit_business.account_api_key = response.account_api_key;
        // edit_business.image_url = response.image_url;
        // edit_business.status = response.status;
        // edit_business.color_code = response.color_code;
        // edit_business.currency_id = response.currency_id;
        // edit_business.currency_name = response.currency_name;
        // edit_business.value = response;
        // if (edit_business.value.status == 1) {
        //     isSwitchOn.value = true;
        // }
        // if (edit_business.value.color_code) {
        //     color_code.value = edit_business.value.color_code;
        // }

        // if (edit_business.value.currency_id) {
        //     edit_select_currency.value.id = edit_business.value.currency_id;
        //     edit_select_currency.value.code = edit_business.value.currency_name;
        // }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'account_api_key' => $this->account_api_key,
            'image_url' => $this->image_url,
            'status' => $this->status,
            'color_code' => $this->color_code,
            'currency_id' => $this->currency_id,
            'currency_name' => $this->currency_name,
            'bill_logo_url' => $this->bill_logo_url,
            'invoice_logo_url' => $this->invoice_logo_url
        ];
    }
}
