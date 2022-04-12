<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\TransferResource;
use App\Models\Car;
use App\Models\City;
use App\Models\Transfer;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index(Request $request)
    {

        return $this->prepareResource($request);

    }


    public function transfers()
    {

    }

    public function prepareResource(Request $request)
    {
        $user = auth()->user();
        $company = $user->company()->id ?? 0;
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'id';
        $sort = $request->get('sort') ?? 'ASC';
        $search = $request->get('search') ?? null;

        if ($limit == "all" && is_null($search) && $orderBy !== "owner") {
            $transfers = Transfer::where('company_id', $company)
                ->with(['cars', 'startCity', 'endCity'])
                ->orderBy($orderBy, $sort)->get();
        } elseif ($limit !== "all" && is_null($search)) {

            $transfers = Transfer::has('cars')->has('startCity')->has('endCity')->where('company_id', $company)
                ->with(['cars', 'startCity', 'endCity'])
                ->orderBy($orderBy, $sort)->paginate($limit);

        } elseif ($search) {
            $transfers = Transfer::where('company_id', $company)
                ->with(['cars', 'startCity', 'endCity'])
                ->orderBy($orderBy, $sort)->get();
        }

        return new TransferResource($transfers);
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_cars' => 'required',
            'city_from' => 'required|not_in:' . $request->city_to,
            'city_to' => 'required|not_in:' . $request->city_from,
            'cancel_time' => 'required',
            'penalty' => 'required',
            'tax' => 'required',
            'price' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required',
        ]);

        $cityFrom = City::where('name', $request->city_from)->first();
        $cityTo = City::where('name', $request->city_to)->first();
        $user = auth()->user();
        $user_id = auth()->user()->id;

        $company = $user->compmay->id ?? 0;
        foreach ($request->selected_cars as $car_id) {
            $transfer = new Transfer();
            $transfer->start_city_id = $cityFrom->id;
            $transfer->finish_city_id = $cityTo->id;
            $transfer->car_id = $car_id;
            $transfer->tax = $request->tax;
            $transfer->price = $request->price;
            $transfer->cancel_time = $request->cancel_time;
            $transfer->penalty = $request->penalty;
            $transfer->company_id = $company;
            $transfer->user_id = $user_id;
            $transfer->description = $request->description;
            $transfer->started_at = $request->started_at;
            $transfer->ended_at = $request->ended_at;
            $transfer->save();
        }
        return $this->prepareResource($request);

    }

    public function show(Transfer $transfer)
    {
        $user = auth()->user();
        $user_id = auth()->user()->id;
        $company = $user->compmay->id ?? 0;

        $transfer->with(['cars', 'startCity', 'endCity']);

        return new TransferResource($transfer);
    }

    public function update(Transfer $transfer, Request $request)
    {
        $request->validate([
            'selected_cars' => 'required',
            'city_from' => 'required|not_in:' . $request->city_to,
            'city_to' => 'required|not_in:' . $request->city_from,
            'cancel_time' => 'required',
            'penalty' => 'required',
            'tax' => 'required',
            'price' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required',
        ]);

    }

    public function getCars()
    {
        $user = auth()->user();
        $company = $user->company()->id ?? 0;

        $cars = Car::where('company_id', $company)->get();

        return $cars;
    }
}
