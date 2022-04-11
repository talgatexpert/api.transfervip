<?php

namespace App\Http\Resources\Api;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{

    public function toArray($request)
    {
        $items = [];
        $items = $this->makeItems($items);
        $roles = Arr::except(User::ROLES, ['super_admin', 'client']);

        $roles = [
            ['name' => User::ROLE_TEXT[$roles['admin']], 'id' => User::ROLES['admin']],
            ['name' => User::ROLE_TEXT[$roles['travel']], 'id' => User::ROLES['travel']],
            ['name' => User::ROLE_TEXT[$roles['company']], 'id' => User::ROLES['company']],
        ];

        $count = !$request->exists('search') ?
            $this->resource->total() ??
            User::whereNot('id', 1)->whereIn('role_id', Arr::except(User::ROLES, ['super_admin', 'client']))->count() :

            $this->resource->count();
        return [
            'users' => $items,
            'roles' => $roles,
            'total' => $count];

    }

    public function makeItems(&$items)
    {
        foreach ($this->resource as $resource) {


            $items[] = [
                'id' => $resource->id,
                'name' => $resource->name,
                'email' => $resource->email,
                'role_id' => $resource->role_id,
                'active' => $resource->active,
                'role' => User::ROLE_TEXT[$resource->role_id],
                'role_info' => [
                    'name' => User::ROLE_TEXT[$resource->role_id],
                    'id' => User::ROLES_ID[$resource->role_id],
                ],
            ];
        }

        return $items;

    }
}
