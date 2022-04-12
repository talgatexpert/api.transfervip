<?php

namespace App\Http\Resources\Api;

use App\Models\City;
use App\Models\Company;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {   $items  = [];
        $items = $this->makeItems($items);
        $count = !$request->exists('search') ? Company::count() : $this->resource->count();

        return [
            'companies' => $items,
            'total' => $count,
        ];
    }

    public function makeItems(&$items)
    {
        foreach ($this->resource as $resource) {
            $items[] = [
                'id' => $resource->id,
                'name' => $resource->name,
                'email' => $resource->user->email,
                'tax' => $resource->tax . '%',
                'phone' => $resource->phone,
                'owner' => $resource->user->name,
                'user_id' => $resource->user_id,
                'active' => $resource->active === 1 ? 'Evet' : 'Yok' ,
            ];
        }

        return $items;

    }
}
