<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FreightOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'order_number' => ['required', 'string', 'max:50', 'unique:freight_orders,order_number'],
            'shipper_company_id' => ['required', 'integer', 'exists:companies,id'],
            'carrier_company_id' => ['nullable', 'integer', 'exists:companies,id'],
            'assigned_driver_id' => ['nullable', 'integer', 'exists:users,id'],
            'origin_location_id' => ['required', 'integer', 'exists:locations,id'],
            'destination_location_id' => ['required', 'integer', 'exists:locations,id'],
            'service_type' => ['required', 'string', Rule::in(['standard', 'express', 'overnight', 'same_day', 'scheduled'])],
            'priority' => ['required', 'integer', 'min:1', 'max:5'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'delivery_date' => ['required', 'date', 'after:pickup_date'],
            'weight' => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'dimensions' => ['nullable', 'array'],
            'dimensions.length' => ['nullable', 'numeric', 'min:0.01', 'max:999.99'],
            'dimensions.width' => ['nullable', 'numeric', 'min:0.01', 'max:999.99'],
            'dimensions.height' => ['nullable', 'numeric', 'min:0.01', 'max:999.99'],
            'special_instructions' => ['nullable', 'string', 'max:1000'],
            'hazardous_materials' => ['boolean'],
            'temperature_controlled' => ['boolean'],
            'signature_required' => ['boolean'],
            'insurance_value' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'cod_amount' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'reference_numbers' => ['nullable', 'array'],
            'reference_numbers.*' => ['string', 'max:100'],
            'customer_po' => ['nullable', 'string', 'max:100'],
            'customer_ref' => ['nullable', 'string', 'max:100'],
        ];

        // For updates, make order_number unique except for current record
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['order_number'] = [
                'required',
                'string',
                'max:50',
                Rule::unique('freight_orders', 'order_number')->ignore($this->route('freight_order'))
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'order_number.required' => 'Order number is required.',
            'order_number.unique' => 'This order number already exists.',
            'shipper_company_id.required' => 'Shipper company is required.',
            'shipper_company_id.exists' => 'Selected shipper company does not exist.',
            'carrier_company_id.exists' => 'Selected carrier company does not exist.',
            'assigned_driver_id.exists' => 'Selected driver does not exist.',
            'origin_location_id.required' => 'Origin location is required.',
            'origin_location_id.exists' => 'Selected origin location does not exist.',
            'destination_location_id.required' => 'Destination location is required.',
            'destination_location_id.exists' => 'Selected destination location does not exist.',
            'service_type.required' => 'Service type is required.',
            'service_type.in' => 'Service type must be one of: standard, express, overnight, same_day, scheduled.',
            'priority.required' => 'Priority is required.',
            'priority.min' => 'Priority must be at least 1.',
            'priority.max' => 'Priority cannot exceed 5.',
            'pickup_date.required' => 'Pickup date is required.',
            'pickup_date.after_or_equal' => 'Pickup date cannot be in the past.',
            'delivery_date.required' => 'Delivery date is required.',
            'delivery_date.after' => 'Delivery date must be after pickup date.',
            'weight.required' => 'Weight is required.',
            'weight.min' => 'Weight must be greater than 0.',
            'weight.max' => 'Weight cannot exceed 99,999.99 lbs.',
            'dimensions.length.min' => 'Length must be greater than 0.',
            'dimensions.width.min' => 'Width must be greater than 0.',
            'dimensions.height.min' => 'Height must be greater than 0.',
            'special_instructions.max' => 'Special instructions cannot exceed 1000 characters.',
            'insurance_value.min' => 'Insurance value cannot be negative.',
            'insurance_value.max' => 'Insurance value cannot exceed $999,999.99.',
            'cod_amount.min' => 'COD amount cannot be negative.',
            'cod_amount.max' => 'COD amount cannot exceed $999,999.99.',
            'reference_numbers.*.max' => 'Each reference number cannot exceed 100 characters.',
            'customer_po.max' => 'Customer PO cannot exceed 100 characters.',
            'customer_ref.max' => 'Customer reference cannot exceed 100 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'shipper_company_id' => 'shipper company',
            'carrier_company_id' => 'carrier company',
            'assigned_driver_id' => 'assigned driver',
            'origin_location_id' => 'origin location',
            'destination_location_id' => 'destination location',
            'service_type' => 'service type',
            'pickup_date' => 'pickup date',
            'delivery_date' => 'delivery date',
            'special_instructions' => 'special instructions',
            'hazardous_materials' => 'hazardous materials flag',
            'temperature_controlled' => 'temperature controlled flag',
            'signature_required' => 'signature required flag',
            'insurance_value' => 'insurance value',
            'cod_amount' => 'COD amount',
            'reference_numbers' => 'reference numbers',
            'customer_po' => 'customer PO',
            'customer_ref' => 'customer reference',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Sanitize string inputs
        if ($this->has('order_number')) {
            $this->merge(['order_number' => trim($this->order_number)]);
        }
        
        if ($this->has('special_instructions')) {
            $this->merge(['special_instructions' => trim($this->special_instructions)]);
        }
        
        if ($this->has('customer_po')) {
            $this->merge(['customer_po' => trim($this->customer_po)]);
        }
        
        if ($this->has('customer_ref')) {
            $this->merge(['customer_ref' => trim($this->customer_ref)]);
        }

        // Sanitize reference numbers array
        if ($this->has('reference_numbers') && is_array($this->reference_numbers)) {
            $sanitized = array_map('trim', $this->reference_numbers);
            $sanitized = array_filter($sanitized); // Remove empty values
            $this->merge(['reference_numbers' => array_values($sanitized)]);
        }

        // Ensure boolean values are properly cast
        $this->merge([
            'hazardous_materials' => (bool) $this->hazardous_materials,
            'temperature_controlled' => (bool) $this->temperature_controlled,
            'signature_required' => (bool) $this->signature_required,
        ]);
    }
}
