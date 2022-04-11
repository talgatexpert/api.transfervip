<?php

namespace App\Http\Resources\Api;

use App\Models\City;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $items = [];
        $items = $this->makeItems($items);
//        if ($this->resource instanceof LengthAwarePaginator) {
//            $total = $this->resource->total();
//            return ['total' => $total, 'cities' => $items];
//
//        }
        $count = !$request->exists('search') ? City::count() : $this->resource->count();
         return ['cities' => $items, 'total' => $count];

    }

    public function makeItems(&$items)
    {
        foreach ($this->resource as $resource) {
            $translations = $resource->getTranslations();
            $items[] = [
                'id' => $resource->id,
                'name' => $resource->name,
                'translations' => $translations['translations'] ?? null,
                'slug' => $resource->slug,
            ];
        }

        return $items;

    }
}
