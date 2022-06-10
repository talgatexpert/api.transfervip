<?php

namespace App\Http\Resources\Api;

use App\Services\CurrencyConverterService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $booking = $this->resource;

        $city_start = $booking->transfer->startCity->getTranslations();
        $city_end = $booking->transfer->endCity->getTranslations();
        $price = $booking->car->getCarPrice($booking, $booking->car, $booking->transfer_id, request('currency') ?? 'TRY', new CurrencyConverterService());

        $returnBooking = $booking->returnBooking;
        $data = [
            'city_from' => $city_start['translations'],
            'city_end' => $city_end['translations'],
            'car' => [
                'id' => $booking->car_id,
                'name' => $booking->car->name,
                'full_name' => $booking->car->getFullName(),
                'baggage_quantity' => $booking->car->baggage_quantity,
                'type' => $booking->car->type,
                'person_quantity' => $booking->car->person_quantity,
            ],
            'price' => $price['one_trip'],
            'price_with_currency' => $price['one_trip'] . ' ' . $booking->currency,
            'return_price' => $price['one_trip'],
            'return_price_with_currency' => $price['one_trip'] . ' ' . $booking->currency,
            'name' => $booking->name,
            'address' => $booking->address,
            'step' => $booking->step,
            'email' => $booking->email,
            'phone' => $booking->phone,
            'currency' => $booking->currency,
            'flight_number' => $booking->flight_number,
            'return_trip' => $booking->return_trip,
            'passengers_number' => $booking->passengers_number,
            'add_child_seat' => $booking->add_child_seat,
            'started_at' => $booking->started_at?->format('d.m.Y'),
            'started_at_time' => $booking->started_at?->format('H:i'),
            'return_booking' => [
                'started_at' => $returnBooking?->started_at?->format('d.m.Y'),
                'started_at_time' => $returnBooking?->started_at?->format('H:i'),
                'flight_number' => $returnBooking?->flight_number,
                'return_trip' => $returnBooking?->return_trip,
                'address' => $returnBooking?->address,

            ],
            'total' => $price['price'],
            'total_with_currency' => $price['price'] . ' ' . $booking->currency,

        ];
        if ($booking->client_confirmed === true) {
            $data = $data + [
                    'pay_type' => $booking->pay_type,
                    'payment_confirmed' => $booking->payment_confirmed,
                    'client_confirmed' => $booking->client_confirmed,
                    'booking_accepted' => $booking->booking_accepted,
                ];
        }

        return $data;

    }
}
