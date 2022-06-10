<?php

namespace App\Http\Requests\Api\Admin;

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
        $request = $this;
        $rules = [
            'city_from' => ['required'],
            'city_end' => ['required'],
            'address' => ['required'],
            'flight_number' => ['required'],
            'started_at' => ['required'],
            'started_at_time' => ['required'],
            'name' => ['required'],
            'email' => ['email', 'required'],
            'passengers_number' => ['required'],
            'phone' => ['required'],
            'add_child_seat' => ['required'],
            'pay_type' => ['required'],
            'transfer_id' => ['required'],
            'car' => ['required'],
        ];
        if ($request->return_trip !== false) {
            $rules += [
                'return_trip_address' => ['required'],
                'return_trip_flight_number' => ['required'],
                'return_trip_started_at' => ['required', 'date', 'after_or_equal:started_at'],
                'return_trip_started_at_time' => ['required'],
            ];
        }
        if ($request->pay_type === "card") {
            $rules = [
                'card.cardNumber' => ['required'],
                'card.expiration' => ['required'],
                'card.name' => ['required'],
                'card.security' => ['required'],
            ];
        }
        return $rules;
    }
}
