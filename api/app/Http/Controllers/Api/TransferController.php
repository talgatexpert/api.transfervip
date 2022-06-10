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


        if (!$cityFrom)
            return response(['message' => 'City from not found', 'success' => false,], 404);
        elseif (!$cityTo)
            return response(['message' => 'City from not found', 'success' => false,], 404);

        $transfers = TransferService::find($cityFrom->id, $cityTo->id);

        return (new TransferResource($transfers))->additional([
            'from' => isset($cityFrom->getTranslations()['translations'][$language]) ? $cityFrom->getTranslations()['translations'][$language] : $cityFrom->name,
            'to' => isset($cityTo->getTranslations()['translations'][$language]) ? $cityTo->getTranslations()['translations'][$language] : $cityTo->name,
        ]);

    }

}
