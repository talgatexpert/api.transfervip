<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Payment extends Model
{
    use HasFactory;

    public const TYPES = ['card', 'cash'];


    public static function getPaymentMethod(string $method): string
    {
        if (in_array($method, self::TYPES)) {
            return     Arr::first(Arr::where(self::TYPES, function ($i) use ($method) {
                return $i == $method;
            }));

        }
        return Arr::first(self::TYPES);
    }
}
