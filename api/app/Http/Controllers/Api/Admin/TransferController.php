<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TransferRequest;
use App\Http\Resources\Api\TransferResource;
use App\Models\Car;
use App\Models\City;
use App\Models\Transfer;
use App\Services\TransferService;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class TransferController extends Controller
{





    public function index(Request $request): TransferResource
    {

        return $this->prepareResource($request);

    }



    public function prepareResource(Request $request): TransferResource
    {

        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'id';
        $sort = $request->get('sort') ?? 'ASC';
        $search = $request->exists('search') ? $request->get('search') : null;


        if ($limit == "all" && is_null($search) && $orderBy !== "owner") {
            $transfers = Transfer::where('company_id', $this->company_id)
                ->with(['cars', 'startCity', 'endCity'])
                ->orderBy($orderBy, $sort)->get();
        } elseif ($limit !== "all" && is_null($search)) {

            $transfers = Transfer::has('cars')->has('startCity')->has('endCity')->where('company_id', $this->company_id)
                ->with(['cars', 'startCity', 'endCity'])
                ->orderBy($orderBy, $sort)->paginate($limit);

        } elseif (!is_null($search)) {

            $transfers = Transfer::whereHas('cars', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('startCity', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhereHas('endCity', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->where('company_id', $this->company_id)
                ->with([
                    'cars',
                    'startCity',
                    'endCity'])
                ->orderBy($orderBy, $sort)->get();
        }


        return new TransferResource($transfers);
    }


    public function store(TransferRequest $request)
    {
        $transfer = new Transfer();
        $transfer = TransferService::store($transfer, $request, $this->company_id, $this->user->id);
        return $this->prepareResource($request);

    }

    public function show(Transfer $transfer)
    {


        $transfer->with(['cars', 'startCity', 'endCity']);

        return new TransferResource($transfer);
    }

    public function update(Transfer $transfer, TransferRequest $request)
    {
        $transfer = TransferService::store($transfer, $request, $this->company_id, $this->user->id);
        return $this->prepareResource($request);
    }




}
