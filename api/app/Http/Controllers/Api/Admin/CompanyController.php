<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CityResource;
use App\Http\Resources\Api\CompanyResource;
use App\Models\City;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return CompanyResource
     */
    public function index(Request $request): CompanyResource
    {
        return $this->prepareResource($request);
    }

    public function prepareResource(Request $request): CompanyResource
    {
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'id';
        $sort = $request->get('sort') ?? 'ASC';
        $search = $request->get('search') ?? null;

        if ($limit == "all" && is_null($search) && $orderBy !== "owner") {
            $companies = Company::orderBy($orderBy, $sort)->get();
        } elseif ($limit !== "all" && is_null($search) && $orderBy !== "owner") {

            $companies = Company::orderBy($orderBy, $sort)->paginate($limit);

        } elseif ($orderBy == "owner" && $limit !== "all") {

            $companies = Company::with(['user' => function ($query) use ($sort) {
                return $query->orderBy('name', $sort);
            }])->paginate($limit);

        }
        elseif ($orderBy === "owner" && $limit == "all" ) {

            $companies = Company::with(['user' => function ($query) use ($sort) {
                return $query->orderBy('name', $sort);
            }])->get();

        }
        elseif ($search) {
            $companies = Company::where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orderBy($orderBy, $sort)->get();
        }

        return new CompanyResource($companies);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return CompanyResource
     */
    public function store(Request $request): CompanyResource
    {
        $request->validate([
            'name' => ['required'],
            'email' => 'email',
            'active' => 'required',
            'tax' => ['integer','required', 'max:90'],
            'phone' => 'required'
        ]);
        $company = new Company();
        $user = User::where('email', $request->email)->first();
        $company->name = $request->name;
        $company->active = $request->active;
        $company->email = $request->email;
        if ($user) {
            $company->user_id = $user->id;
        }
        $company->tax = $request->tax;
        $company->phone = $request->phone;
        $company->agree_terms = 1;

        $company->save();

        return $this->prepareResource($request);
    }


    public function update(Request $request, Company $company): CompanyResource
    {
        $request->validate([
            'name' => ['required'],
            'email' => 'email',
            'active' => 'required',
            'tax' => ['required', 'max:90'],
            'phone' => 'required'
        ]);
        $user = User::where('email', $request->email)->first();
        $company->name = $request->name;
        $company->active = $request->active;
        $company->email = $request->email;
        if ($user) {
            $company->user_id = $user->id;
        }
        $company->tax = $request->tax;
        $company->phone = $request->phone;
        $company->agree_terms = 1;

        $company->save();

        return $this->prepareResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Company $company
     * @return CompanyResource
     */
    public function destroy(Request $request, Company $company): CompanyResource
    {
        $company->delete();
        return $this->prepareResource($request);
    }
}
