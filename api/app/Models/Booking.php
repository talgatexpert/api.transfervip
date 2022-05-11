<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Booking extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'add_child_seat',
        'address',
        'step',
        'booking_token',
        'car_id',
        'email',
        'flight_number',
        'name',
        'passengers_number',
        'phone',
        'return_trip',
        'transfer_id',
        'currency',
    ];
    protected $casts = [

        'return_trip' => 'boolean',
        'client_confirmed' => 'boolean',

    ];

    protected $dates = ['started_at'];

    public const ACCEPTED = 1;
    public const PAY_TYPES = ['card', 'cash'];
    public const NOT_ACCEPTED = 0;

    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function returnBooking()
    {
        return $this->hasOne(Booking::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function setBookingToken()
    {
        $this->booking_token = Str::uuid();
        $this->save();
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
        $this->save();
    }

    public function setReturnTrip($return_trip)
    {
        $this->return_trip = $return_trip;
        $this->save();
    }

    public function setClientConfirmed()
    {
        $this->client_confirmed = self::ACCEPTED;
    }

    public function setBookingAccept()
    {
        $this->booking_accepted = self::ACCEPTED;
    }

    public function setPaymentConfirmed()
    {
        $this->payment_confirmed = self::ACCEPTED;
    }

    public function setClientConfirm($type)
    {
        $this->payment_confirmed = self::ACCEPTED;
        $this->pre_paid = false;
        $this->pay_type = $type;
        $this->client_confirmed =  self::ACCEPTED;
        $this->step = 'finish';
        $this->save();

    }

    public function setStartedDate($date, $time)
    {
        if (is_array($time)) {
            $time = $time['HH'] . ":" . $time['mm'];
        }
        $date = trim(preg_replace('/[^0-9\s+:.]/', '', $date));
        $time = trim(preg_replace('/[^0-9\s+:.]/', '', $time));

        $this->started_at = $date && $time ? Carbon::createFromFormat('d.m.Y H:m', $date . ' ' . $time)->format('Y-d-m H:m:i') : null;
        $this->save();

    }
}
