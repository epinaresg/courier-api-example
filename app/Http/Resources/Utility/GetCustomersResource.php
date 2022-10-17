<?php

namespace App\Http\Resources\Utility;

use Illuminate\Http\Resources\Json\JsonResource;

class GetCustomersResource extends JsonResource
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
            'id' => $this->id,
			'label' => (($this->business_name && $this->business_number ? $this->business_number . ' | ' . $this->business_name  : $this->first_name . ' ' . $this->last_name)),

            'first_name' => $this->first_name,
            'last_name' => $this->last_name,

            'business_name' => $this->business_name,
            'business_number' => $this->business_number,
        ];
    }
}
