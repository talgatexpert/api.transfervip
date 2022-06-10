<?php

namespace App\Http\Resources\Api;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransferBookingResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resource instanceof LengthAwarePaginator || $this->resource instanceof Collection) {
            return [
                'next' => $this->resource->nextPageUrl(),
                'prev' => $this->resource->previousPageUrl(),
                'last' => $this->resource->lastPage(),
                'last_page_url' => $this->resource->toArray()['last_page_url'],
                'current' => $this->resource->currentPage(),
                'total' => $this->resource->count(),
                'items' => $this->items(),


            ];
        }
        return [
            'transfer' => $this->prepare($this->resource),
        ];


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
            'direction' => City::getByLanguage($translation_start['translations']) . ' - ' . City::getByLanguage($translation['translations']),
            'start_city' => array_merge(['id' => $resource->startCity->id, 'name' => $resource->startCity->name], $translation_start),
            'end_city' => array_merge(['id' => $resource->endCity->id, 'name' => $resource->endCity->name], $translation),
            'tax' => $resource->company->tax . '%',
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
        $user = auth()->user();
        $current_user_tax = 1;
        if ($user->isTravel() && $user->has('company')) {
            $current_user_tax = $user->company->tax;
        }
        $result = [];
        foreach ($cars as $car) {
            $price_db = DB::table('transfer_cars')->select('price')->where('transfer_id', $resource->id)->where('car_id', $car->id)->first();
            $one_percent = ($price_db->price / 100);
            $company_tax = isset($car->company) ? $car?->company?->tax * $one_percent : 0;
            $agency_tax = $current_user_tax * $one_percent;
            $agency_tax_price = $agency_tax + $price_db->price;
            $company_tax_price = $company_tax + $price_db->price;
            if ($user->isTravel()) {
                $price = $price_db->price + $company_tax + $agency_tax;
            } else {
                $price = $price_db->price + $company_tax;
            }
            if ($user->isAdmin() || $user->isSuperAdmin()){
                $price = $price_db->price;
                $company_tax = 0;
                $company_tax_price = 0;
                $agency_tax_price = 0;
                $agency_tax = 0;
            }

            $result[] = [
                'id' => $car->id,
                'name' => $car->name,
                'model' => $car->model,
                'full_name' => $car->name . ' ' . $car->model,
                'image' => $car->image,
                'type' => $car->type,
                'company_tax' => ceil($company_tax),
                'agency_tax' => ceil($agency_tax),
                'agency_tax_price' => ceil($agency_tax_price),
                'company_tax_price' => ceil($company_tax_price),
                'price' => ceil($price),
                'currency' => 'TRY',
                'price_db' => ceil($price_db->price),
                'person_quantity' => $car->person_quantity,
                'baggage_quantity' => $car->baggage_quantity,
                'company_id' => $user?->company->id ?? 0,
                'user_id' => $car->user_id,
                'transfer_id' => $resource->id
            ];
        }
        return $result;

    }
}
