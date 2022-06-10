<?php

namespace App\Services;

use App\Http\Requests\Api\Admin\BookingRequest;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public static function store(BookingRequest $request)
    {
//        $request
//        $request = $request->request;

        $request = json_decode(json_encode( $request->all(), JSON_FORCE_OBJECT));

        $price_db = DB::table('transfer_cars')->select('price')->where('transfer_id', $request->transfer_id)->where('car_id', $request->car->id)->first();
        $user = auth()->user();
        $car = Car::findOrFail($request->car->id);
        $current_user_tax = 1;
        if ($user->isTravel() && $user->has('company')) {
            $current_user_tax = $user->company->tax;
        }
        $one_percent = ($price_db->price / 100);
        $company_tax = isset($car->company) ? $car?->company?->tax * $one_percent : 0;
        $agency_tax = $current_user_tax * $one_percent;
        $agency_tax_price = $agency_tax + $price_db->price;
        $company_tax_price = $company_tax + $price_db->price;
        if ($user->isTravel()) {
            $price = $price_db->price + $company_tax + $agency_tax;
        } else {
            $price = $price_db->price + $company_tax;
        }
        if ($user->isAdmin() || $user->isSuperAdmin()){
            $price = $price_db->price;
            $company_tax = 0;
            $company_tax_price = 0;
            $agency_tax_price = 0;
            $agency_tax = 0;
        }


        $booking = Booking::create([
            'booking_token' => Str::uuid(),
            'transfer_id' => $request->transfer_id,
            'company_id' => $request->car->company_id,
            'name' => $request->name,
            'return_trip' => $request->return_trip,
            'currency' => $request->car->currency,
            'email' => $request->email,
            'phone' => $request->phone,
            'amount' => $request->return_trip ? $price * 2 : $price,
            'address' => $request->address,
            'flight_number' => $request->flight_number,
            'passengers_number' => $request->passengers_number,
            'add_child_seat' => $request->add_child_seat,
            'client_confirmed' => Booking::ACCEPTED,
            'booking_accepted' => Booking::NOT_ACCEPTED,
            'pay_type' => Payment::getPaymentMethod($request->pay_type),
            'payment_confirmed' => Booking::NOT_ACCEPTED,
            'pre_paid' => Booking::NOT_ACCEPTED,
            'company_tax' => $company_tax,
            'agency_tax' => $agency_tax,
            'car_id' => $request->car->id,
            'price_one_trip' => $price_db->price,
        ]);


        if ($request->return_trip) {
            $returnBooking = $booking->returnBooking;
            if (!$booking->returnBooking) {

                $returnBooking = Booking::create([
                    'add_child_seat' => $request->add_child_seat,
                    'address' => $request->return_trip_address,
                    'flight_number' => $request->return_trip_flight_number,
                    'transfer_id' => $booking->transfer_id,
                    'company_tax' => $company_tax,
                    'amount' => $request->return_trip ? $price * 2 : $price,
                    'agency_tax' => $agency_tax,
                    'client_confirmed' => Booking::ACCEPTED,
                    'pay_type' => Payment::getPaymentMethod($request->pay_type),
                    'car_id' => $request->car->id,
                    'price_one_trip' => $price_db->price,
                    'company_id' => $request->car->company_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'passengers_number' => $request->passengers_number,
                    'phone' => $request->phone,
                ]);

            }
            $returnBooking->setParentId($booking->id);
            $returnBooking->setStartedDate($request->return_trip_started_at, $request->return_trip_started_at_time, true);
        }

        $booking->setStartedDate($request->started_at, $request->started_at_time, true);
        $booking->setClientConfirmed();
        $booking->save();

        return $booking->with('returnBooking')->first();
    }

}