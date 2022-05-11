<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->user() == null) {
            return false;
        }
        if (auth()->user()) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'selected_cars' => 'required',
            'city_from' => 'required|exists:cities,id|not_in:' . $this->city_to,
            'city_to' => 'required|exists:cities,id|not_in:' . $this->city_from,
            'cancel_time' => 'required',
            'penalty' => 'required',
            'tax' => 'required',
            'selected_cars.*.price' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required',
        ];
    }
}
