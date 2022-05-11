<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'booking_token' => 'required|uuid',
            'started_at' => 'required',
            'started_at_time' => 'required',
//            'return_booking.address' => 'required',
//            'return_booking.started_at' => 'required',
//            'return_booking.started_at_time' => 'required',
            'email' => 'email',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'flight_number' => 'required',
            'passengers_number' => 'required'
        ];
    }
}
