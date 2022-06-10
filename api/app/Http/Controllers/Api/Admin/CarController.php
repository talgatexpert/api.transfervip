<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CarResource;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index(Request $request): CarResource
    {
        return $this->prepareResource($request);
    }

    public function prepareResource(Request $request): CarResource
    {
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'id';
        $sort = $request->get('sort') ?? 'ASC';
        $search = $request->get('search') ?? null;

        $user = auth()->user();
        $company = $user->company->id ?? 0;

        if ($limit == "all" && is_null($search) && $orderBy !== "owner") {
            $cars = Car::with('company')->where('company_id', $company)->orderBy($orderBy, $sort)->get();
        } elseif ($limit !== "all" && is_null($search) && $orderBy !== "owner") {

            $cars = Car::with('company')->where('company_id', $company)->orderBy($orderBy, $sort)->paginate($limit);

        } elseif ($orderBy == "owner" && $limit !== "all") {

            $cars = Car::with('company')->where('company_id', $company)->with(['user' => function ($query) use ($sort) {
                return $query->orderBy('name', $sort);
            }])->paginate($limit);

        } elseif ($orderBy === "owner" && $limit == "all") {

            $cars = Car::with('company')->where('company_id', $company)->with(['user' => function ($query) use ($sort) {
                return $query->orderBy('name', $sort);
            }])->get();

        } elseif ($search) {
            $cars = Car::with('company')->where('company_id', $company)->where('name', 'like', '%' . $search . '%')
                ->orWhere('model', 'like', '%' . $search . '%')
                ->orWhere('type', 'like', '%' . $search . '%')
                ->orderBy($orderBy, $sort)->paginate($limit);
        }

        return new CarResource($cars);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return CarResource
     */
    public function store(Request $request): CarResource
    {
        $user = auth()->user();
        $company = $user->company->id ?? 0;
        $request->validate([
            'name' => ['required'],
            'model' => 'required',
            'active' => 'required',
            'type' => ['required'],
            'person_quantity' => 'required',
            'baggage_quantity' => 'required',
        ]);


        $car = new Car();
        $car->name = $request->name;
        $car->model = $request->model;
        $car->type = $request->type;
        $car->model = $request->model;
        $car->person_quantity = $request->person_quantity;
        $car->baggage_quantity = $request->baggage_quantity;
        $car->user_id = $user->id;
        $car->company_id = $company;
        $car->image = $this->saveFile($request);
        $car->active = $request->active !== "false" ? 1 : 0;

        $car->save();

        return $this->prepareResource($request);
    }


    private function saveFile(Request $request): null|string
    {
        if ($request->hasFile('new_image')) {
            $image = $request->file('new_image');
            $fileName = Str::random(18) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/images', $fileName);
            return  Storage::disk('public')->url('public/images/' . $fileName);
        }
        return $request->image;
    }

    public function update(Request $request, Car $car): CarResource
    {
        $request->validate([
            'name' => 'required',
            'model' => 'required',
            'active' => 'required',
            'type' => 'required',
            'person_quantity' => 'required',
            'baggage_quantity' => 'required',
            'user_id' => 'required',
            'company_id' => 'required',
        ]);
        $car->name = $request->name;
        $car->model = $request->model;
        $car->type = $request->type;
        $car->model = $request->model;
        $car->person_quantity = $request->person_quantity;
        $car->baggage_quantity = $request->baggage_quantity;
        $car->user_id = $request->user_id;
        $car->company_id = $request->company_id;
        $car->image = $this->saveFile($request);
        $car->active = $request->active !== "false" ? 1 : 0;

        $car->save();

        return $this->prepareResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Car $car
     * @return CarResource
     */
    public function destroy(Request $request, Car $car): CarResource
    {
        $car->delete();
        return $this->prepareResource($request);
    }
}
