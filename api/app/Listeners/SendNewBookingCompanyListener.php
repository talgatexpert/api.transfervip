<?php

namespace App\Listeners;

use App\Events\TransferBookedEvent;
use App\Models\Booking;
use App\Models\User;
use App\Notifications\SendNewBookingCompanyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewBookingCompanyListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle(TransferBookedEvent $bookedEvent)
    {
        $booking = $bookedEvent->booking;

        if ($booking->transfer->company)
            Notification::send([$booking->transfer->company], new SendNewBookingCompanyNotification($booking));
        else {
            $users2 = User::super()->get();
            $users = User::admin()->get();
            Notification::send($users, new SendNewBookingCompanyNotification($booking));
            Notification::send($users2, new SendNewBookingCompanyNotification($booking));
        }
    }
}
