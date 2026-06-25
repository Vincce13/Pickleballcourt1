<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'full_name'      => ['required', 'string', 'min:2', 'max:100'],
            'mobile_number'  => ['required', 'regex:/^09\d{9}$/'],
            'email'          => ['required', 'email', 'max:150'],
            'court_id'       => ['required', 'integer', 'between:0,2'],
            'booking_date'   => ['required', 'date', 'after_or_equal:today'],
            'time_slots'     => ['required', 'string'],  // JSON string from JS
            'payment_method' => ['required', 'in:GCash,PayMaya,Bank Transfer,Pay at the counter'],
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required'      => 'Please enter your full name.',
            'mobile_number.required'  => 'Please enter your mobile number.',
            'mobile_number.regex'     => 'Enter a valid PH mobile number (09XXXXXXXXX).',
            'email.required'          => 'Please enter your email address.',
            'email.email'             => 'Please enter a valid email address.',
            'court_id.required'       => 'Please select a court.',
            'booking_date.required'   => 'Please select a date.',
            'booking_date.after_or_equal' => 'Booking date must be today or in the future.',
            'time_slots.required'     => 'Please select at least one time slot.',
            'payment_method.required' => 'Please select a payment method.',
        ];
    }
}