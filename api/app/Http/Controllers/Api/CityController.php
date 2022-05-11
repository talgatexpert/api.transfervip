<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\NotFoundResource;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'turkish';
        $exceptCity = $request->get('city') ?? '';

        $sort = $request->get('sort') ?? 'ASC';
        if ($search) {

            $cities = match ($orderBy) {
                "russian" => City::where(function ($query) use ($exceptCity) {
                    if ($exceptCity) {
                        return $query->where('translations->russian', '!=', $exceptCity);
                    }
                    return $query;
                })
                    ->where('translations->russian', 'like', '%' . $search . '%')
                    ->orderBy('translations->russian', $sort)->limit($limit)->get(),
                "english" => City::where(function ($query) use ($exceptCity) {
                    if ($exceptCity) {
                        return $query->where('translations->english', '!=', $exceptCity);
                    }
                    return $query;
                })->where('translations->english', 'like', '%' . $search . '%')
                    ->orderBy('translations->english', $sort)->limit($limit)->get(),
                default => City::
                where(function ($query) use ($exceptCity) {
                    if ($exceptCity) {
                        return $query->where('translations->turkish', '!=', $exceptCity);
                    }
                    return $query;
                })->where('translations->turkish', 'like', '%' . $search . '%')
                    ->orderBy('translations->turkish', $sort)->limit($limit)->get(),
            };

            if ($cities->count() > 0) {
                return new CityResource($cities);

            }
            return new NotFoundResource(['message' => 'City not found']);
        }
        return new NotFoundResource(['message' => 'City not found']);

    }

    public function show($cityName, Request $request)
    {
        try {
            $city = City::where('translations->' . $request->language, $cityName)->first();
            if ($city) {
                $translations = $city->getTranslations();
                return response([
                    'success' => true,
                    'data' => [
                        'id' => $city->id,
                        'name' => $translations['translations'][$request->language ?? 'turkish'] ?? null,
                        'slug' => $city->slug,
                    ]
                ]);
            } else {
                return response([
                    'success' => false,
                    'data' => []
                ]);
            }
        } catch (\Exception $exception) {
            return response([
                'success' => false,
                'data' => []
            ]);
        }

    }
}
