<?php

namespace App\Http\Resources\Api\Admin;

use App\Models\Booking;
use App\Services\CurrencyConverterService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
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
        if ($this->resource instanceof LengthAwarePaginator || $this->resource instanceof Collection) {
            $items = [];
            foreach ($this->resource as $booking) {

                $city_start = $booking->transfer->startCity->getTranslations();
                $city_end = $booking->transfer->endCity->getTranslations();

                $prices = $booking->getPriceFromAmount();
                $returnBooking = $booking->returnBooking;
                $taxes = $booking->getTaxes();

                $data = [
                    'id'                                 => $booking->id,
                    'city_from'                          => $city_start['translations'] + ['id' => $booking->transfer->startCity->id],
                    'city_end'                           => $city_end['translations'] + ['id' => $booking->transfer->endCity->id],
                    'car'                                => $booking->car->getFullName(),
                    'car_id'                             => $booking->car->id,
                    'price'                              => $prices['one_trip'],
                    'price_with_currency'                => $prices['one_trip_with_currency'],
                    'return_price'                       => $prices['return_trip'],
                    'return_price_with_currency'         => $prices['return_trip_with_currency'],
                    'name'                               => $booking->name,
                    'address'                            => $booking->address,
                    'step'                               => $booking->step,
                    'email'                              => $booking->email,
                    'phone'                              => $booking->phone,
                    'currency'                           => $booking->currency,
                    'flight_number'                      => $booking->flight_number,
                    'return_trip'                        => $booking->return_trip ? 'Evet' : 'Yok',
                    'passengers_number'                  => $booking->passengers_number,
                    'add_child_seat'                     => $booking->add_child_seat ?: '-',
                    'started_at'                         => $booking->started_at?->format('d.m.Y H:i'),
                    'started_at_time'                    => $booking->started_at?->format('H:i'),
                    'company_tax'                        => $taxes['company_tax'],
                    'company_tax_with_currency'          => $taxes['company_tax_with_currency'],
                    'agency_tax'                         => $taxes['agency_tax'],
                    'agency_tax_with_currency'           => $taxes['agency_tax_with_currency'],
                ];
                if ($booking->return_trip) {

                    $data += [
                        'return_trip_started_at' => $returnBooking?->started_at?->format('d.m.Y H:i'),
                        'return_trip_started_at_time' => $returnBooking?->started_at?->format('H:i'),
                        'return_trip_flight_number' => $returnBooking?->flight_number,
                        'return_trip_address' => $returnBooking?->address,

                    ];
                } else {
                    $data += [
                        'return_trip_started_at' => '-',
                        'return_trip_started_at_time' => '-',
                        'return_trip_flight_number' => '-',
                        'return_trip_address' => '-',

                    ];
                }

                $data += [
                    'pay_type' => $booking->pay_type,
                    'payment_confirmed' => $booking->payment_confirmed,
                    'client_confirmed' => $booking->client_confirmed,
                    'booking_accepted' => $booking->booking_accepted,
                    'total' => $prices['total'],
                    'total_with_currency' => $prices['total_with_currency']
                ];

                $items[] = $data;
            }

            return [
                'bookings' => $items,
                'total' => $this->resource->count()
            ];
        } else {
            $booking = $this->resource;
            $city_start = $booking->transfer->startCity->getTranslations();
            $city_end = $booking->transfer->endCity->getTranslations();


            $prices = $booking->getPriceFromAmount();
            $returnBooking = $booking->returnBooking;

            $taxes = $booking->getTaxes();
            $data = [
                'id'                                 => $booking->id,
                'city_from'                          => $city_start['translations'] + ['id' => $booking->transfer->startCity->id],
                'city_end'                           => $city_end['translations'] + ['id' => $booking->transfer->endCity->id],
                'car'                                => $booking->car->getFullName(),
                'car_id'                             => $booking->car->id,
                'price'                              => $prices['one_trip'],
                'price_with_currency'                => $prices['one_trip_with_currency'],
                'return_price'                       => $prices['return_trip'],
                'return_price_with_currency'         => $prices['return_trip_with_currency'],
                'name'                               => $booking->name,
                'address'                            => $booking->address,
                'step'                               => $booking->step,
                'email'                              => $booking->email,
                'phone'                              => $booking->phone,
                'currency'                           => $booking->currency,
                'flight_number'                      => $booking->flight_number,
                'return_trip'                        => $booking->return_trip ? 'Evet' : 'Yok',
                'passengers_number'                  => $booking->passengers_number,
                'add_child_seat'                     => $booking->add_child_seat ?: '-',
                'started_at'                         => $booking->started_at?->format('d.m.Y H:i'),
                'started_at_time'                    => $booking->started_at?->format('H:i'),
                'company_tax_with_currency'          => $taxes['company_tax_with_currency'],
                'agency_tax_with_currency'           => $taxes['agency_tax_with_currency'],
            ];
            if ($booking->return_trip) {

                $data += [
                    'return_trip_started_at' => $returnBooking?->started_at?->format('d.m.Y H:i'),
                    'return_trip_started_at_time' => $returnBooking?->started_at?->format('H:i'),
                    'return_trip_flight_number' => $returnBooking?->flight_number,
                    'return_trip_address' => $returnBooking?->address,

                ];
            } else {
                $data += [
                    'return_trip_started_at' => '-',
                    'return_trip_started_at_time' => '-',
                    'return_trip_flight_number' => '-',
                    'return_trip_address' => '-',

                ];
            }

            $data += [
                'pay_type' => $booking->pay_type,
                'payment_confirmed' => $booking->payment_confirmed,
                'client_confirmed' => $booking->client_confirmed,
                'booking_accepted' => $booking->booking_accepted,
                'total' => $prices['total'],
                'total_with_currency' => $prices['total_with_currency']
            ];

            return $data;
        }
    }
}
