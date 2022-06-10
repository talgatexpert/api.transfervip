<?php

namespace App\Models;

use App\Events\TransferBookedEvent;
use App\Services\CurrencyConverterService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Booking extends Model
{

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'company_tax',
        'agency_tax',
        'price_one_trip',
        'client_id',
        'pay_type',
        'amount',
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
        'client_confirmed'
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
        $this->save();
        event(new TransferBookedEvent($this));
    }

    public function getPrice()
    {
        $price = DB::table('transfer_cars')->select('price')->where('transfer_id', $this->transfer->id)->where('car_id', $this->car_id)->first();
        return $price;
    }


    private function convert($price, $currency): float|int
    {
        return (new  CurrencyConverterService())->convert($price, $currency);
    }

    protected function getComanyTax()
    {
        $agency_tax = null;
        $company_tax = null;
        if ($this->company?->user?->getRole() == 'travel') {
            $agency_tax = $this->company->tax;
        }
        if ($this->transfer?->user?->getRole() == 'company') {
            $company_tax = $this->transfer->user->company->tax;
        }
        return [$agency_tax, $company_tax];
    }

    public function setParentId($id)
    {
        $this->booking_id = $id;
        $this->save();
    }

    public function getTaxes(): array
    {
        $price = $this->getPrice();

        [$agency_tax, $company_tax] = $this->getComanyTax();


        if ((int)$this->return_trip)
            $booking_without_tax_price = $this->convert($this->amount / 2, $this->currency);
        else
            $booking_without_tax_price = $this->convert($this->amount, $this->currency);

        $data['booking_without_tax_price'] = $booking_without_tax_price;
        $data['company_tax'] = '-';
        $data['agency_tax'] = '-';
        $data['company_tax_with_currency'] = '-';
        $data['agency_tax_with_currency'] = '-';

        if (!is_null($company_tax)) {
            $data['company_tax'] = (int)ceil($booking_without_tax_price / 100 * $company_tax);
            $data['company_tax_with_currency'] = (int)ceil($booking_without_tax_price / 100 * $company_tax) . ' ' . $this->currency;
        }
        if (!is_null($agency_tax)) {
            $data['agency_tax'] = (int)ceil($booking_without_tax_price / 100 * $agency_tax);
            $data['agency_tax_with_currency'] = (int)ceil($booking_without_tax_price / 100 * $agency_tax) . ' ' . $this->currency;
        }

        return $data;

    }

    public function getPriceFromAmount()
    {
        $taxes_and_prices = $this->getTaxes();

        $price = $taxes_and_prices['booking_without_tax_price'];
        $return_trip = $this->return_trip;
        $booking_accepted = $this->booking_accepted;


        $one_trip = ceil($this->amount / 2 + ($taxes_and_prices['company_tax'] !== '-' ? $taxes_and_prices['company_tax'] : 0) + ($taxes_and_prices['agency_tax'] !== '-' ? $taxes_and_prices['agency_tax'] : 0));
        $amount = ceil($this->amount + ($taxes_and_prices['company_tax'] !== '-' ? $taxes_and_prices['company_tax'] : 0) + ($taxes_and_prices['agency_tax'] !== '-' ? $taxes_and_prices['agency_tax'] : 0));
        $price = ceil($price + ($taxes_and_prices['company_tax'] !== '-' ? $taxes_and_prices['company_tax'] : 0) + ($taxes_and_prices['agency_tax'] !== '-' ? $taxes_and_prices['agency_tax'] : 0));

        if ($booking_accepted && $return_trip) {

            return [
                'one_trip' => $one_trip,
                'one_trip_with_currency' => $one_trip . ' ' . $this->currency,
                'return_trip' => $one_trip,
                'return_trip_with_currency' => $one_trip . ' ' . $this->currency,
                'total' => $amount,
                'total_with_currency' => $amount . ' ' . $this->currency,
            ];
        } elseif (!$booking_accepted && !$return_trip) {
            return [
                'one_trip' => $price,
                'one_trip_with_currency' => $price . ' ' . $this->currency,
                'return_trip' => '',
                'return_trip_with_currency' => '',
                'total' => $price,
                'total_with_currency' => $price . ' ' . $this->currency,
            ];
        } elseif (!$booking_accepted && $return_trip) {
            return [
                'one_trip' => $price,
                'one_trip_with_currency' => $price . ' ' . $this->currency,
                'return_trip' => $price,
                'return_trip_with_currency' => $price . ' ' . $this->currency,
                'total' => ceil($price * 2),
                'total_with_currency' => $price * 2 . ' ' . $this->currency,
            ];
        } elseif ($booking_accepted && !$return_trip) {
            return [
                'one_trip' => $price,
                'one_trip_with_currency' => $price . ' ' . $this->currency,
                'return_trip' => '',
                'return_trip_with_currency' => '',
                'total' => $price,
                'total_with_currency' => $price . ' ' . $this->currency,
            ];
        }
    }

    public function setBookingAccept()
    {
        if ($this->booking_accepted !== self::ACCEPTED)
            $this->booking_accepted = self::ACCEPTED;
        else
            $this->booking_accepted = self::NOT_ACCEPTED;
        $this->save();
    }

    public function scopeWhereNotAccepted($query)
    {
        $user = auth()->user();
        $query->join('transfers', 'transfers.id', '=', 'bookings.transfer_id')->where(function ($q) use ($user) {
            return $q->where('transfers.user_id', $user->id)->orWhere('transfers.company_id', $user?->company?->id);
        })->whereNull('bookings.booking_id')->where('client_confirmed', self::ACCEPTED)->whereNull('booking_id')->where('booking_accepted', self::NOT_ACCEPTED)->orWhereNull('booking_accepted')->where('client_confirmed', self::ACCEPTED)->whereNull('booking_id')->where('booking_accepted', self::NOT_ACCEPTED);
    }

    public function setBookingAmount()
    {
        $price = $this->getPrice();
        $converterService = new  CurrencyConverterService();
        $currency = $this->currency;
        $tax = $this->company?->tax;
        $rates = $converterService->convert($price->price, $currency);

        $withTax = ceil((ceil($rates) / 100 * $tax) + $rates);
        $ifCurrencyRateNotWorking = ceil((ceil($price->price) / 100 * $tax) + $price->price);
        $price = $rates !== false ? $withTax : $ifCurrencyRateNotWorking;
        $this->amount = $this->return_trip != false ? ceil($price + $price) : ceil($price);
        $this->save();
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
        $this->client_confirmed = self::ACCEPTED;
        $this->step = 'finish';
        $this->save();
        event(new TransferBookedEvent($this));


    }

    public function setStartedDate($date, $time, $admin = false)
    {
        if (is_array($time)) {
            $time = $time['HH'] . ":" . $time['mm'];
        }


        if ($admin)
            $this->started_at = $date && $time ? Carbon::createFromFormat('Y-m-d H:m', $date . ' ' . $time)->format('Y-d-m H:m:i') : null;
        else {
            $date = trim(preg_replace('/[^0-9\s+:.]/', '', $date));
            $time = trim(preg_replace('/[^0-9\s+:.]/', '', $time));
            $this->started_at = $date && $time ? Carbon::createFromFormat('d.m.Y H:m', $date . ' ' . $time)->format('Y-d-m H:m:i') : null;
        }
        $this->save();

    }
}
