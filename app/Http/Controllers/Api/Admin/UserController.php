<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\UserResource;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserResource
     */
    public function index(Request $request): UserResource
    {
        return $this->prepareResource($request);
    }

    public function prepareResource(Request $request): UserResource
    {
        $limit = $request->get('limit') ?? 10;
        $orderBy = $request->get('orderby') ?? 'id';
        $sort = $request->get('sort') ?? 'ASC';
        $search = $request->get('search') ?? null;

        if ($limit == "all" && is_null($search)) {
            $users = User::whereNot('id', 1)->orderBy($orderBy, $sort)
                ->whereIn('role_id', Arr::except(User::ROLES, ['super_admin', 'client']))
                ->get();
        } elseif ($limit !== "all" && is_null($search)) {
            $users = User::whereNot('id', 1)->orderBy($orderBy, $sort
            )->whereIn('role_id', Arr::except(User::ROLES, ['super_admin', 'client']))
                ->paginate($limit);
        } elseif ($search) {
            $users = User::whereNot('id', 1)->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->whereIn('role_id', Arr::except(User::ROLES, ['super_admin', 'client']))
                ->orderBy($orderBy, $sort)->get();
        }

        return new UserResource($users);
    }


    public function user()
    {
        $user = auth()->user();

        if ((int)$user->role_id === User::ROLES['super_admin']) {
            $token = $user->createToken('SUPER_ADMIN', User::ABILITIES['SUPER_ADMIN']);
        }
        if ((int)$user->role_id === User::ROLES['admin']) {
            $token = $user->createToken('ADMIN', User::ABILITIES['ADMIN_ABILITIES']);
        }
        if ((int)$user->role_id === User::ROLES['client']) {
            $token = $user->createToken('CLIENT', User::ABILITIES['CLIENT_ABILITIES']);
        }
        if ((int)$user->role_id === User::ROLES['travel']) {
            $token = $user->createToken('TRAVEL', User::ABILITIES['TRAVEL_ABILITIES']);
        }
        if ((int)$user->role_id === User::ROLES['company']) {
            $token = $user->createToken('COMPANY', User::ABILITIES['TRAVEL_ABILITIES']);
        }

        return response([
            'name' => $user->name,
            'role_id' => (int)$user->role_id,
            'role' => User::ROLE_TEXT[(int)$user->role_id],
            'hidden_role' => $user->getRole(),
            'permissions' => $user->getPermissions()
        ]);

    }

    /*
     * @var $user<User>
     */
    public function roles()
    {
        $user = auth()->user();
        return response([
            'data' => [
                'user' => [
                    'name' => $user->name,
                    'role' => $user->getRole(),
                    'permissions' => $user->getPermissions(),

                ]
            ]
        ]);
    }


    public function getNotBindUsers(Request $request): UserResource
    {
        $request->validate([
            'email' => 'required'
        ]);

        $email = $request->email;
        $users = User::
        where(function ($query) {
            $query->doesnthave('company');
            $query->where('role_id', User::ROLES['admin']);
            $query->orWhere('role_id', User::ROLES['company']);
            $query->orWhere('role_id', User::ROLES['travel']);
        })->orWhere(function ($query) use ($email) {
            $query->has('company')
                ->where('email', 'LIKE', '%' . $email . '%');


        })->whereNot('id', 1)
            ->where('email', 'LIKE', '%' . $email . '%')
            ->paginate(5);

        return new UserResource($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return UserResource
     */
    public function store(Request $request): UserResource
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['email', 'unique:users,email'],
            'password' => 'required',
            'role' => 'required',
            'active' => 'required',
        ]);
        $user = new User();
        $user->name = $request->get('name');
        $user->email = $request->email;
        $user->role_id = $request->role;
        $user->password = $request->password;
        $user->active = $request->exists('active') ? 1 : 0;
        $user->save();
        return $this->prepareResource($request);
    }


    public function update(Request $request, User $user): UserResource
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['email', Rule::unique('users')->ignore($user->id)],
            'role' => 'required',
            'active' => 'required',
        ]);
        $user->name = $request->get('name');
        $user->email = $request->email;
        $user->active = $request->active;
        $user->role_id = $request->role;
        $user->save();
        return $this->prepareResource($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return UserResource
     */
    public function destroy(Request $request, User $user): UserResource
    {
        $user->company()->delete();
        $user->delete();
        return $this->prepareResource($request);
    }
}
