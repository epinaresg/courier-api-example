<?php

namespace App\Http\Requests\Shipment;

use Illuminate\Foundation\Http\FormRequest;

class ShipmentCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'customer_id' => 'required',
            'vehicle_id' => 'required',

            'tasks' => 'required',
            'tasks.*.order' => 'required|numeric',
            'tasks.*.type' => 'required|in:pickup,dropoff',
            'tasks.*.date' => 'required|date|date_format:Y-m-d',
            'tasks.*.package_content' => 'required',
            'tasks.*.package_instruction' => 'string',
            'tasks.*.address' => 'required',
            'tasks.*.address_reference' => 'string',
            'tasks.*.delivery_zone_id' => 'required',
            'tasks.*.contact_full_name' => 'required',
            'tasks.*.contact_phone_code' => 'required|regex:/^\+[0-9-]+$/s',
            'tasks.*.contact_phone_number' => 'required|numeric',
            'tasks.*.contact_email' => 'email',
            'tasks.*.payment_method_id' => 'string',
            'tasks.*.total_receivable' => 'string',
        ];
    }
}
