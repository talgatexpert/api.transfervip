<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BookingRequest;
use App\Http\Resources\Api\BookingResource;
use App\Mail\BookingNotificartioToAdmin;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $booking = Booking::create($request->only('transfer_id', 'car_id', 'currency', 'return_trip'));

        $booking->company_id = $booking->transfer?->company?->id ?? 0;
        $booking->save();

        $booking->setBookingToken();
        return response([
            'success' => true,
            'booking_token' => $booking->booking_token
        ]);
    }

    public function show($booking_token)
    {
        $booking = Booking::where('booking_token', $booking_token)->with('transfer', 'returnBooking')->first();
        if (!$booking) {
            return response(['success' => false, 'message' => 'Booking not found'], 404);

        }
        return new BookingResource($booking);
    }

    public function confirmed($booking_token)
    {
        $booking = Booking::where('booking_token', $booking_token)->with('transfer', 'returnBooking')->first();
        if (!$booking) {
            return response(['success' => false, 'message' => 'Booking not found'], 404);

        }
        return new BookingResource($booking);
    }

    public function update(BookingRequest $request, $booking_token)
    {
        $booking = Booking::where('booking_token', $booking_token)->with('transfer')->first();
        if (!$booking) {
            return response(['success' => false, 'message' => 'Booking not found'], 404);

        }
        if (!$booking->client_confirmed) {


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
        }

        return new BookingResource($booking);

    }

    public function updateCurrency(Request $request, $booking_token)
    {
        $booking = Booking::where('booking_token', $request->booking_token)->first();
        if ($booking) {
            $booking->setCurrency($request->currency);
            return new BookingResource($booking);
        }
        return response(['success' => false, 'message' => 'Booking not found']);

    }

    public function updateReturnTrip(Request $request, $booking_token)
    {
        $booking = Booking::where('booking_token', $request->booking_token)->first();
        if ($booking) {
            $booking->setReturnTrip($request->return_trip);
            return new BookingResource($booking);
        }
        return response(['success' => false, 'message' => 'Booking not found']);
    }

    public function confirm(Request $request, $booking_token)
    {
        $booking = Booking::where('booking_token', $booking_token)->first();
        if ($booking)
            if ($request->payment_method == 'cash') {
                $booking->setClientConfirm($request->payment_method);
                $companyEmail = $booking?->compnay?->email ?? env('SUPER_ADMIN_EMAIL');
                Mail::to($companyEmail)->send(new BookingNotificartioToAdmin($booking));
                return new BookingResource($booking);
            }
        return response(['success' => false, 'message' => 'Booking not found']);


    }
}
