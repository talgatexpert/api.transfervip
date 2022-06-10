<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Admin\BookingRequest;
use App\Http\Resources\Api\Admin\BookingResource;
use App\Http\Resources\Api\TransferBookingResource;
use App\Models\Booking;
use App\Models\Client;
use App\Services\BookingService;
use App\Services\TransferService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return BookingResource
     */

    public function index()
    {


        $bookings = Booking::with('transfer')->select('bookings.*')->join('transfers', 'transfers.id', '=', 'bookings.transfer_id')
            ->where(function ($q) {
                if (!$this->user->isAdminOrSuper())
                    $q->where(function ($q2) {
                        $q2 = $q2->where('transfers.user_id', $this->user->id);
                        if ($this->user->company) {
                            $q2 .= $q2->orWhere('transfers.company_id', $this->user?->company?->id ?? null)->orWhere('bookings.company_id', $this->user?->company?->id ?? null);
                        }
                    })->orWhere('bookings.company_id', $this->user?->company?->id ?? null);
            })->whereNull('bookings.booking_id')->where('client_confirmed', Booking::ACCEPTED)->orderBy('bookings.id', $request->orderby ?? 'DESC')
            ->distinct()
            ->paginate($request->limit ?? 15);

        return new BookingResource($bookings);
    }

    /**
     * Display the specified resource.
     *
     * @param BookingRequest $request
     * @return TransferBookingResource
     */

    public function transfers(Request $request): TransferBookingResource
    {
        $city_from = $request->city_from;
        $city_end = $request->city_end;

        $transfers = TransferService::find($city_from, $city_end, $request->limit ?? 30);

        return new TransferBookingResource($transfers);
    }

    /**
     * Display the specified resource.
     *
     * @param BookingRequest $request
     * @return Response
     */

    public function store(BookingRequest $request): Response
    {
        $booking = BookingService::store($request);
        return response(['success' => true, 'data' => $booking]);

    }

    /**
     * Display the specified resource.
     *
     * @param Booking $booking
     * @return BookingResource
     */
    public function show(Booking $booking) : BookingResource
    {
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Booking $booking
     * @return BookingResource
     */
    public function update(Request $request, Booking $booking): BookingResource
    {
        $booking->setBookingAccept();
        $booking->setBookingAmount();

        $bookings = Booking::where('client_confirmed', Booking::ACCEPTED)->orderBy('id', $request->orderby ?? 'DESC')->get();
        return new BookingResource($bookings);

    }

    public function count(): Response|Application|ResponseFactory
    {
        $count = Booking::whereNotAccepted()->count();
        return response(['count' => $count]);
    }

}
