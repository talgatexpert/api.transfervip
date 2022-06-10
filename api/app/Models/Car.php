<?php

namespace App\Models;

use App\Services\CurrencyConverterService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Car extends Model
{
    use HasFactory, SoftDeletes;

    const ACTIVE = 1;


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class, 'car_id', 'id', 'bookings');
    }

    public function scopeActive($q)
    {
        return $q->where('active', 1);
    }

    public function getFullName(): string
    {
        return $this->name . ' ' . $this->model;
    }

    public function is_active(): bool
    {
        return $this->active === self::ACTIVE;
    }

    public static function getCarPrice(Booking|bool $booking, Car $car, $transfer_id, $currency, CurrencyConverterService $converterService): array
    {
        $price_db = DB::table('transfer_cars')->select('price')->where('transfer_id', $transfer_id)->where('car_id', $car->id)->first();
        $one_percent = ($price_db->price / 100);
        $company_tax = isset($car->company) ? $car->company->tax * $one_percent : 0;

        $price = $converterService->convert($price_db->price + $company_tax, $currency);
        $price_one = $price;

        if (isset($booking->return_trip))
            if ($booking->return_trip)
                $price = $price * 2;

        else
            if (request('return_trip') === 'true') {
                $price = $price * 2;
            }

        return ['price' => ceil($price), 'company_tax' => ceil($company_tax), 'one_trip' => ceil($price_one)];
    }


}
