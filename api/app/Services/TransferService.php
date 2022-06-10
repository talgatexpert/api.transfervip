<?php

namespace App\Services;

use App\Http\Requests\Api\TransferRequest;
use App\Models\Car;
use App\Models\Company;
use App\Models\Transfer;
use Illuminate\Support\Facades\DB;

class TransferService
{

    public static function store(Transfer $transfer, TransferRequest $request, $company_id, $user_id): Transfer
    {
        $transfer->start_city_id = $request->city_from;
        $transfer->finish_city_id = $request->city_to;
        $transfer->tax = $request->tax;
        $transfer->cancel_time = $request->cancel_time;
        $transfer->penalty = $request->penalty;
        $transfer->company_id = $company_id;
        $transfer->user_id = $user_id;
        $transfer->description = $request->description;
        $transfer->started_at = $request->started_at;
        $transfer->ended_at = $request->ended_at;
        $transfer->save();
        $items = collect($request->selected_cars);

        $ids = $items->map(function ($item) {
            return $item['id'];
        })->toArray();

        $transfer->setCars($ids);

        foreach ($items as $item) {
            $db = DB::table('transfer_cars')->where('car_id', $item['id'])->where('transfer_id', $transfer->id);
            $db->update(['price' => $item['price']]);
        }

        return $transfer;
    }

    public static function find(int $city_from_id, int $city_to_id, $limit = 50, string $min_date = null, string $max_date = null, )
    {
        $transfer = Transfer::select('transfers.*')->with(['cars' => function($q) {
            $q->with('company');
        }])->where(function ($q) use ($city_to_id, $city_from_id){
            $q->where('start_city_id', $city_from_id)->where('finish_city_id', $city_to_id);

        })->orWhere(function ($q)  use ($city_to_id, $city_from_id){
            $q->where('start_city_id', $city_to_id)->where('finish_city_id',  $city_from_id);

        });
        if (!is_null($min_date)) {
            $transfer = $transfer->whereDate('started_at', '>=', $min_date);
        }
        if (!is_null($max_date)) {
            $transfer = $transfer->whereDate('started_at', '>=', $max_date);
        }

      $transfer = $transfer->join('transfer_cars', 'transfer_cars.transfer_id', '=', 'transfers.id')
            ->join('cars', 'cars.id', '=', 'transfer_cars.car_id')
            ->join('companies', 'companies.id', '=', 'cars.company_id')
            ->where('cars.active', Car::ACTIVE)
            ->where('companies.active', Company::ACTIVE)
            ->distinct();


        return $transfer->paginate($limit);



//        return $transfer->map(function ($item) {
//           $item->cars = $item->cars->filter(fn($item) => $item->is_active());
//            if ((int)$item->company_id !== 0) {
//                if ($item->company->is_active()) {
//                    return $item;
//                }
//            }
//            if ((int)$item->company_id === 0) {
//                return $item;
//            }
//        });


    }
}
