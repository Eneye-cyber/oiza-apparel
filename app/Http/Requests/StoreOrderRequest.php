<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation (runs before rules are applied).
     */
    protected function prepareForValidation(): void
    {
        // Log all input data (filter sensitive fields if needed, e.g., via $this->except(['password']))
        Log::info('Incoming order request data:', $this->all());
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'phone' => 'required|string|max:15',

            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',

            'country' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip' => 'nullable|string|max:20',

            // Billing (only if not "same as shipping")
            'billing_same_as_shipping' => ['required', 'boolean'],
            'billing_country' => ['required_if_declined:billing_same_as_shipping'],
            'billing_first_name' => ['required_if_declined:billing_same_as_shipping'],
            'billing_last_name' => ['required_if_declined:billing_same_as_shipping'],
            'billing_address' => ['required_if_declined:billing_same_as_shipping'],
            'billing_city' => ['required_if_declined:billing_same_as_shipping'],
            'billing_state' => ['required_if_declined:billing_same_as_shipping'],
            'billing_zip' => ['required_if_declined:billing_same_as_shipping'],

            'shipping_method' => 'required|integer ',
            'order_notes' => 'nullable|string|max:1000',
            'save_information' => 'required|boolean',
            'marketing_opt_in' => 'required|boolean',

            // 'terms_agreement' => 'accepted',
        ];
    }
}
