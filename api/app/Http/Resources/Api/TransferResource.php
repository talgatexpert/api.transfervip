<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

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
        $items = $this->makeItems();
        if ($this->resource instanceof  Collection){
            return [
                'total' => $this->resource->count(),
                'transfers' => $items,
            ];
        }
        return [
            'transfer' => $items,
        ];


    }

    public function makeItems()
    {
        $items = [];

        if ($this->resource instanceof Collection){
            foreach ($this->resource as $resource) {
                $translation_start = $resource->startCity->getTranslations();
                $translation = $resource->endCity->getTranslations();


                $items[] = [
                    'id' => $resource->id,
                    'direction' => $translation_start['translations']['turkish'] . ' - ' . $translation['translations']['turkish'],
                    'start_city' => array_merge(['id' => $resource->startCity->id, 'name' => $resource->startCity->name], $translation_start),
                    'end_city' => array_merge(['id' => $resource->endCity->id, 'name' => $resource->endCity->name], $translation),
                    'car_id' => $resource->car_id,
                    'tax' => $resource->tax . '%',
                    'price' => $resource->price,
                    'cancel_time' => $resource->cancel_time,
                    'description' => $resource->description,

                    'penalty' => $resource->penalty . '%',
                    'company_id' => $resource->company_id,
                    'user_id' => $resource->user_id,
                    'started_at' => $resource->started_at->format('d.m.Y'),
                    'ended_at' => $resource->ended_at->format('d.m.Y'),
                    'created_at' => $resource->created_at->format('d.m.Y'),
                    'updated_at' => $resource->updated_at->format('d.m.Y'),
                    'cars' => [
                        'id' => $resource->cars->id,
                        'name' => $resource->cars->name,
                        'model' => $resource->cars->model,
                        'full_name' => $resource->cars->name . ' ' . $resource->cars->model,
                        'image' => $resource->cars->image,
                        'type' => $resource->cars->type,
                        'person_quantity' => $resource->cars->person_quantity,
                        'baggage_quantity' => $resource->cars->baggage_quantity,
                        'company_id' => $resource->cars->company_id,
                        'user_id' => $resource->cars->user_id,
                    ],
                ];
            }
        }else{
            $resource = $this->resource;
            $translation_start = $resource->startCity->getTranslations();
            $translation = $resource->endCity->getTranslations();


            $items = [
                'id' => $resource->id,
                'direction' => $translation_start['translations']['turkish'] . ' - ' . $translation['translations']['turkish'],
                'start_city' => array_merge(['id' => $resource->startCity->id, 'name' => $resource->startCity->name], $translation_start),
                'end_city' => array_merge(['id' => $resource->endCity->id, 'name' => $resource->endCity->name], $translation),
                'car_id' => $resource->car_id,
                'tax' => $resource->tax . '%',
                'description' => $resource->description,
                'price' => $resource->price,
                'cancel_time' => $resource->cancel_time,
                'penalty' => $resource->penalty . '%',
                'company_id' => $resource->company_id,
                'user_id' => $resource->user_id,
                'started_at' => $resource->started_at,
                'ended_at' => $resource->ended_at,
                'created_at' => $resource->created_at->format('d.m.Y'),
                'updated_at' => $resource->updated_at->format('d.m.Y'),
                'cars' => [
                    'id' => $resource->cars->id,
                    'name' => $resource->cars->name,
                    'model' => $resource->cars->model,
                    'full_name' => $resource->cars->name . ' ' . $resource->cars->model,
                    'image' => $resource->cars->image,
                    'type' => $resource->cars->type,
                    'person_quantity' => $resource->cars->person_quantity,
                    'baggage_quantity' => $resource->cars->baggage_quantity,
                    'company_id' => $resource->cars->company_id,
                    'user_id' => $resource->cars->user_id,
                ],
            ];
        }

        return $items;
    }
}
