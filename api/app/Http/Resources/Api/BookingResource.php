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
        $price = DB::table('transfer_cars')->select('price')->where('transfer_id', $booking->transfer->id)->where('car_id', $booking->car_id)->first();
        $converterService = new  CurrencyConverterService();

        $currency = $booking->currency;
        $tax = $booking->company?->tax;
        $rates = $converterService->convert($price->price, $currency);

        $withTax = ceil((ceil($rates) / 100 * $tax) + $rates);
        $ifCurrencyRateNotWorking = ceil((ceil($price->price) / 100 * $tax) + $price->price);
        $price = $rates !== false ? $withTax : $ifCurrencyRateNotWorking;

        $returnBooking = $booking->returnBooking;
        return
            [
                'city_from' => $city_start['translations'],
                'city_end' => $city_end['translations'],
                'car' => [
                    'name' => $booking->car->name,
                    'full_name' => $booking->car->getFullName(),
                    'baggage_quantity' => $booking->car->baggage_quantity,
                    'type' => $booking->car->type,
                    'person_quantity' => $booking->car->person_quantity,
                ],
                'price' => $price,
                'price_with_currency' => $price . ' ' . $booking->currency,
                'return_price' => $price,
                'return_price_with_currency' => $price . ' ' . $booking->currency,
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
                'total' => $booking->return_trip != false ? $price + $price : $price,
                'total_with_currency' => $booking->return_trip != false ? $price + $price  . ' ' . $booking->currency  : $price  . ' ' . $booking->currency,

            ];
    }
}
