<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\TransferResource;
use App\Models\City;
use App\Models\Transfer;
use App\Services\CurrencyConverterService;
use App\Services\TransferService;
use Illuminate\Http\Request;

class TransferController extends Controller
{


    public function index($cityFrom, $cityTo, Request $request)
    {
        $cityFrom = City::whereSlug($cityFrom)->first();

        $cityTo = City::whereSlug($cityTo)->first();
        $language = $request->get('language') ?? 'turkish';
        $currency = $request->get('currency') ?? null;

        if (is_null($cityFrom))
            return response(['message' => 'City from not found', 'success' => false,], 404);
        elseif (is_null($cityTo))
            return response(['message' => 'City from not found', 'success' => false,], 404);

        $transfers = TransferService::find($cityFrom->id, $cityTo->id);

        return new TransferResource($transfers, );

    }

}
