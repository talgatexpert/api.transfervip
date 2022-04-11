<?php

namespace App\Http\Resources\Api;

use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{

    public function toArray($request)
    {   $items  = [];
        $items = $this->makeItems($items);
        $count = !$request->exists('search') ? Car::count() : $this->resource->count();

        return [
            'cars' => $items,
            'total' => $count,
            'company_id' => auth()->user()->company->id ?? 0,
            'user_id' => auth()->user()->id,
        ];
    }

    public function makeItems(&$items)
    {
        foreach ($this->resource as $resource) {
            $items[] = [
                'id' => $resource->id,
                'full_name' => $resource->name . ' ' . $resource->model,

                'name' => $resource->name,
                'model' => $resource->model,
                'type' => $resource->type ,
                'car_type' => ['name' => $resource->type] ,
                'person_quantity' => $resource->person_quantity,
                'baggage_quantity' => $resource->baggage_quantity,
                'image' => $resource->image,
                'user_id' => $resource->user_id,
                'company_id' => $resource->company_id,
                'active' => $resource->active === 1 ? 'Evet' : 'Yok' ,
            ];
        }

        return $items;

    }
}
