<?php

namespace App\Services;

use App\Http\Requests\Api\TransferRequest;
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
            $db = DB::table('transfer_cars')->where('car_id', $item['id']);
            $db->update(['price' => $item['price']]);
        }

        return $transfer;
    }

    public static function find(int $city_from_id, int $cit_to_id, string $min_date = null, string $max_date = null, $limit = 50)
    {
        $transfer = Transfer::where('start_city_id', $city_from_id)->where('finish_city_id', $cit_to_id);
        if (!is_null($min_date)) {
            $transfer = $transfer->whereDate('started_at', '>=', $min_date);
        }
        if (!is_null($max_date)) {
            $transfer = $transfer->whereDate('started_at', '>=', $max_date);
        }

        return $transfer->paginate($limit);

    }
}
