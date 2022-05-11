<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookingRequest;
use App\Http\Resources\Api\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $booking = Booking::create($request->only('transfer_id', 'car_id', 'currency'));

        $booking->company_id = $booking->transfer?->company->id;
        $booking->save();

        $booking->setBookingToken();
        return response([
            'success' => true,
            'booking_token' => $booking->booking_token
        ]);
    }

    public function show($booking_token)
    {
        $booking = Booking::where('booking_token', $booking_token)->with('transfer', 'returnBooking')->firstOrFail();
        return new BookingResource($booking);
    }

    public function update(BookingRequest $request, $booking_token)
    {
        $booking = Booking::where('booking_token', $booking_token)->with('transfer')->firstOrFail();

        if ($request->return_trip == true) {
            if (!$booking->returnBooking) {
                $returnBooking = Booking::create([
                    'add_child_seat' => $request->add_child_seat,
                    'address' => $request->return_booking['address'] ?? null,
                    'flight_number' => $request->return_booking['flight_number'] ?? null,
                    'transfer_id' => $booking->transfer_id,
                    'car_id' => $booking->car_id,
                ]);
                $returnBooking->booking_id = $booking->id;
                $returnBooking->save();
                $returnBooking->setStartedDate($request->return_booking['started_at'] ?? null, $request->return_booking['started_at_time'] ?? null);
                $returnBooking->update($request->only(
                    'add_child_seat',
                    'car_id',
                    'email',
                    'name',
                    'passengers_number',
                    'phone',
                    'transfer_id'
                ));


            } else {
                $booking->returnBooking->update([
                    'add_child_seat' => $request->add_child_seat,
                    'address' => $request->return_booking['address'] ?? null,
                    'flight_number' => $request->return_booking['flight_number'] ?? null,

                ]);
                $booking->returnBooking->update($request->only(
                    'add_child_seat',
                    'car_id',
                    'email',
                    'name',
                    'passengers_number',
                    'phone',
                    'transfer_id',
                    'currency',

                ));
                $booking->returnBooking->setStartedDate($request->return_booking['started_at'] ?? null, $request->return_booking['started_at_time'] ?? null);

            }

        }

        $booking->update($request->only(
            'add_child_seat',
            'address',
            'car_id',
            'currency',
            'email',
            'flight_number',
            'name',
            'passengers_number',
            'phone',
            'return_trip',
            'transfer_id'
        ) + ['step' => $request?->step]);

        $booking->setStartedDate($request->started_at, $request->started_at_time);

        return new BookingResource($booking);

    }
}
