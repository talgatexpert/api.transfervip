<?php

namespace App\Http\Resources\Api\Client;

use App\Services\CurrencyConverterService;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransferResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if ($this->resource instanceof LengthAwarePaginator || $this->resource instanceof Collection) {
            return [
                'total' => $this->resource->count(),
                'items' => $this->items(),
            ];
        }
    }

    public function items(): array
    {
        $items = [];


        if ($this->resource instanceof LengthAwarePaginator || $this->resource instanceof Collection) {
            foreach ($this->resource as $resource) {
                $items[] = $this->prepare($resource);
            }
        }

        return $items;
    }

    public function prepare($resource): array
    {
        $translation_start = $resource->startCity->getTranslations();
        $translation = $resource->endCity->getTranslations();
        return [
            'id' => $resource->id,
            'direction' => $translation_start['translations']['turkish'] . ' - ' . $translation['translations']['turkish'],
            'start_city' => array_merge(['id' => $resource->startCity->id, 'name' => $resource->startCity->name], $translation_start),
            'end_city' => array_merge(['id' => $resource->endCity->id, 'name' => $resource->endCity->name], $translation),
            'tax' => $resource->tax . '%',
            'cancel_time' => $resource->cancel_time,
            'description' => $resource->description,
            'penalty' => $resource->penalty . '%',
            'company_id' => $resource->company_id,
            'user_id' => $resource->user_id,
            'started_at' => $resource->started_at->format('d.m.Y'),
            'ended_at' => $resource->ended_at->format('d.m.Y'),
            'created_at' => $resource->created_at->format('d.m.Y'),
            'updated_at' => $resource->updated_at->format('d.m.Y'),
            'cars' => $this->makeCars($resource->cars, $resource),
        ];
    }


    public function makeCars(Collection $cars, $resource): array
    {
        $converterService = new  CurrencyConverterService();
        $currency = request('currency') ?? null;

        $result = [];
        foreach ($cars as $car) {
            $price = DB::table('transfer_cars')->select('price')->where('transfer_id', $resource->id)->where('car_id', $car->id)->first();
            $tax = $resource->company?->tax;
            $rates = $converterService->convert($price->price, $currency);
            $withTax = ceil(($rates / 100 * $tax) + $rates);
            $ifCurrencyRateNotWorking = ceil((ceil($price->price) / 100 * $tax) + $price->price);
            $price = $rates !== false ? $withTax : $ifCurrencyRateNotWorking;
            $price = request('return_trip') == 'true' ? $price * 2 : $price;
            $result[] = [
                'id' => $car->id,
                'name' => $car->name,
                'model' => $car->model,
                'full_name' => $car->name . ' ' . $car->model,
                'image' => $car->image,
                'type' => $car->type,
                'price' => $price,
                'person_quantity' => $car->person_quantity,
                'baggage_quantity' => $car->baggage_quantity,
            ];
        }
        return $result;

    }
}
