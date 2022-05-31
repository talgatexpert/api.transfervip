<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Admin\BookingResource;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingControler extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BookingResource
     */
    public function index()
    {
        $bookings = Booking::where('client_confirmed', Booking::ACCEPTED)->orderBy('id', $request->orderby ?? 'DESC')->get();

        return new BookingResource($bookings);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Booking::create([
            'company_id' => 0,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Booking $booking)
    {
        $booking->setBookingAccept();
        $booking->setBookingAmount();

        $bookings = Booking::where('client_confirmed', Booking::ACCEPTED)->orderBy('id', $request->orderby ?? 'DESC')->get();
        return new BookingResource($bookings);

    }

    public function count()
    {
        $count = Booking::whereNotAccepted()->count();
        return response(['count' => $count]);
    }

}
