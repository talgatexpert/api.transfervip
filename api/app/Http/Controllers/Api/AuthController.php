<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\Api\LoginRequest;

class AuthController extends Controller
{

    public function createUser(Request $request)
    {
        $user = new User();

        $user->name = "Sergey Brich";
        $user->password = bcrypt($request->password);
        $user->email = $request->email;
        $user->save();

    }

    public function login(LoginRequest $request): Response
    {
//        $this->createUser($request);


        if (auth()->attempt($request->validated())) {
            $user = auth()->user();

            $token = explode('|', $this->generateToken($user));
            $user->api_token  = $token[1];
            $user->save();

            return response( ['data' =>
                ['user' => $user,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => (int)$user->role_id,
                'role' => User::ROLE_TEXT[(int)$user->role_id],
                'hidden_role' => $user->getRole(),
                'permissions' => $user->getPermissions(),
                'token' => $token[1],
                'company_id' => $user?->company?->id ?? null
                ]]);

        } else {
            return response(['errors' => [
                'message' => 'The provided credentials are incorrect.',
                'status' => 'INVALID_DATA'
            ]], 422);
        }
    }

    public function generateToken($user)
    {
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

        return $token->plainTextToken;
    }



    public function logout(): Response
    {
        $user = auth()->user();
        $user->tokens()->delete();
        $user->api_token  = null;
        $user->save();
        auth()->logout();
        return response(['message' => 'Successfully logout']);
    }

}
