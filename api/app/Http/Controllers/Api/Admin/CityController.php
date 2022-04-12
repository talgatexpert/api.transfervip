<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\NotFoundResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CityController extends Controller
{



    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CityResource
     */
    public function index(Request $request): CityResource
    {
        $user = auth()->user();
        if (!$user->tokenCan('cities:get')){
            abort(403, 'Unauthorized');
        }


        return $this->prepareResource($request);
    }

    public function prepareResource(Request $request): CityResource
    {
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'turkish';
        $language = $request->get('language') ?? null;
        $sort = $request->get('sort') ?? 'ASC';
        $search = $request->get('search') ?? null;
        $except = $request->get('except') ?? null;

        if ($limit == "all" && is_null($search)) {
            $cities = City::where(function ($query) use ($except, $language) {
                if ($except) {
                    return $query->where('translations->' . $language, '!=', $except);
                }
                return $query;
            })
                ->orderBy('translations->' . $orderBy, $sort)->get();
        } elseif ($limit !== "all" && is_null($search)) {
            $cities = City::where(function ($query) use ($except, $language) {
                if ($except) {
                    return $query->where('translations->' . $language, '!=', $except);
                }
                return $query;
            })
                ->orderBy('translations->' . $orderBy, $sort)->paginate($limit);
        } elseif ($search) {
            if ($language){
                $cities = City::where(function ($query) use ($except, $language) {
                    if ($except) {
                        return $query->where('translations->' . $language, '!=', $except);
                    }
                    return $query;
                })
                    ->where('translations->' . $language, 'like', '%' . $search . '%')
                    ->orderBy('translations->' . $orderBy, $sort)->paginate($limit);
            }else{
                $cities = City::where(function ($query) use ($except) {
                    if ($except) {
                        return $query->where('translations->turkish', '!=', $except);
                    }
                    return $query;
                })
                    ->where('translations->russian', 'like', '%' . $search . '%')
                    ->orWhere('translations->english', 'like', '%' . $search . '%')
                    ->orWhere('translations->turkish', 'like', '%' . $search . '%')
                    ->orderBy('translations->' . $orderBy, $sort)->paginate($limit);
            }

        }

        return new CityResource($cities);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return CityResource
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user->tokenCan('cities:add')){
            abort(403, 'Unauthorized');
        }
        $city = new City();
        $city->name = $request->get('name');
        $city->setTranslations('translations', $request->get('cities'));
        $city->save();
        return $this->prepareResource($request);
    }





    public function update(Request $request, City $city)
    {
        $user = auth()->user();
        if (!$user->tokenCan('cities:edit')){
            abort(403, 'Unauthorized');
        }
        $city->name = $request->get('name');
        $city->setTranslations('translations', $request->get('cities'));
        $city->save();
        return $this->prepareResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param City $city
     * @return CityResource
     */
    public function destroy(Request $request, City $city): CityResource
    {
        $user = auth()->user();
        if (!$user->tokenCan('cities:delete')){
            abort(403, 'Unauthorized');
        }
        $city->delete();
        return $this->prepareResource($request);
    }
}
