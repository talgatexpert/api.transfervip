<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $items = [];
        foreach ($this->resource as $resource){
            $translations = $resource->getTranslations();
            $items[] = [
                'id' => $resource->id,
                'name' => $translations['translations'][$request->get('orderby') ?? 'turkish'] ?? null,
                'slug' => $resource->slug,
            ];
        }
        return [
            'cities' => $items
        ];
    }
}
