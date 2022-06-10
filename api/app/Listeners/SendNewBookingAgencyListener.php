<?php

namespace App\Listeners;

use App\Events\TransferBookedEvent;
use App\Models\User;
use App\Notifications\SendNewBookingAgencyNotification;
use App\Notifications\SendNewBookingCompanyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewBookingAgencyListener
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
     * @param  object  $event
     * @return void
     */
    public function handle(TransferBookedEvent $bookedEvent)
    {
        $booking = $bookedEvent->booking;

        if ($booking->company)
            Notification::send([$booking->company], new SendNewBookingAgencyNotification($booking));

    }
}
