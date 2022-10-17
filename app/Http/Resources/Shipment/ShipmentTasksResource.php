<?php

namespace App\Http\Resources\Shipment;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentTasksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "order" => $this->order,
            "type" => $this->type,

            "package_content" => $this->package_content,
            "package_instruction" => $this->package_instruction,

            "address" => $this->address,
            "address_reference" => $this->address_reference,

            "delivery_zone" => [
                "id" => $this->deliveryZone->id,
                "name" => $this->deliveryZone->name,
            ],

            "contact" => [
                "full_name" => $this->contact_full_name,
                "phone" => "{$this->contact_phone_code} {$this->contact_phone_number}",
                "phone_code" => $this->contact_phone_code,
                "phone_number" => $this->contact_phone_number,
                "email" =>  $this->email,
            ],

            "payment_method" => (
                $this->paymentMethod ? [
                    "id" => $this->paymentMethod->id,
                    "name" => $this->paymentMethod->name,
                ] : null
            ),
            "total_receivable" => $this->total_receivable,
        ];
    }
}
