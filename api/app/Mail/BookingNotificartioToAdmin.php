<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingNotificartioToAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $booking = $this->booking;
        $city_start = $booking->transfer->startCity->getTranslations();
        $city_end = $booking->transfer->endCity->getTranslations();
        $returnBooking = $booking->returnBooking;
        $data = [
            'id' => $booking->id,
            'city_from' => $city_start['translations'],
            'city_end' => $city_end['translations'],
            'car' => [
                'name' => $booking->car->name,
                'full_name' => $booking->car->getFullName(),
                'baggage_quantity' => $booking->car->baggage_quantity,
                'type' => $booking->car->type,
                'person_quantity' => $booking->car->person_quantity,
            ],
            'price' => $booking->amount,
            'price_with_currency' => $booking->amount . ' ' . $booking->currency,
            'return_price' => $booking->return_amount,
            'return_price_with_currency' => $booking->return_amount . ' ' . $booking->currency,
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
            'total' => $booking->return_trip != false ? $booking->amount + $booking->return_amount : $booking->amount,
            'total_with_currency' => $booking->return_trip != false ? $booking->amount + $booking->return_amount . ' ' . $booking->currency : $booking->amount . ' ' . $booking->currency,

        ];
        if ($booking->client_confirmed === true) {
            $data = $data + [
                    'pay_type' => $booking->pay_type,
                    'payment_confirmed' => $booking->payment_confirmed,
                    'client_confirmed' => $booking->client_confirmed,
                    'booking_accepted' => $booking->booking_accepted,
                ];
        }
        return $this->view('booking_admin', $data);
    }
}
