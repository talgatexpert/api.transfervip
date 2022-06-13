<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\Client\TransferResource;
use App\Models\City;
use App\Models\Transfer;
use App\Services\CurrencyConverterService;
use App\Services\TransferService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TransferController extends Controller
{


    public function index($cityFrom, $cityTo, Request $request): Response|TransferResource
    {
        $cityFrom = City::whereSlug($cityFrom)->first();

        $cityTo = City::whereSlug($cityTo)->first();
        $language = $request->get('language') ?? 'turkish';
        $limit = $request->get('limit') ?? 50;
        $currency = $request->get('currency') ?? null;


        if (!$cityFrom)
            return response(['message' => 'City from not found', 'success' => false,], 404);
        elseif (!$cityTo)
            return response(['message' => 'City to not found', 'success' => false,], 404);

        $transfers = TransferService::find($cityFrom->id, $cityTo->id, $limit, now()->format('Y-m-d H:i'));

        return (new TransferResource($transfers))->additional([
            'from' => isset($cityFrom->getTranslations()['translations'][$language]) ? $cityFrom->getTranslations()['translations'][$language] : $cityFrom->name,
            'to' => isset($cityTo->getTranslations()['translations'][$language]) ? $cityTo->getTranslations()['translations'][$language] : $cityTo->name,
        ]);

    }

}
