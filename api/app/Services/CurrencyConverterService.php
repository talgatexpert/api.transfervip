<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;

class CurrencyConverterService
{

    private function _convert(array $convert)
    {
        try {

            $url = 'https://api.apilayer.com/fixer/latest?base=' . config('app.base_currency') . '&symbols=' . rtrim(implode(',', $convert), ',');
            $response = Http::withHeaders(['apikey' => config('app.fixer_api_key')])->get($url);
            $result = $response->object();
            if ($result->success === true) {
                Cache::put('currency_' . $convert[0], json_encode($result), 600);
                $currency = new Currency();
                $currency->base = $result->base;
                $currency->rates = json_encode($result->rates);
                $currency->rate = $convert[0];
                $currency->base_rate = $result->rates->{$convert[0]};
                $currency->date_api = $result->date;
                $currency->save();
            }

        } catch (\Exception $exception) {
            Log::error('CURRENCY API ' . $exception->getMessage() . $exception->getLine());
        }
    }


    public function convert(string $amount, string|null $convert): float|int|array
    {
        if (is_null($convert) || mb_strtoupper($convert) === "TRY") {
            return $amount;
        }

        try {
            if (!\cache()->get('currency_' . $convert)) {
                $this->_convert(Arr::wrap($convert));
            }
            $currency = Currency::where('rate', $convert)->get()->last();

            if (!$currency) {
                $this->_convert(Arr::wrap($convert));
                $currency = Currency::where('rate', $convert)->get()->last();
            }
            if ($currency)
                return $currency->base_rate * $amount;
            else
                return false;
        } catch (Exception $exception) {
            $currency = Currency::where('rate', $convert)->get()->last();
            $rates = json_decode($currency->rates);

            return $currency->base_rate  * $amount;
        }
    }
}