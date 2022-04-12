<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class NotFoundResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function withResponse($request, $response)
    {
        $response->setStatusCode(Response::HTTP_NOT_FOUND);
    }
    public function toArray($request)
    {

        return [
            'message' => $this->resource['message'],
        ];
    }
}
