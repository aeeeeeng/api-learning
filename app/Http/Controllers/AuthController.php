<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(AuthRequest $request)
    {
        $userValues = $request->only(['name', 'email', 'password']);
        $userValues['password'] = Hash::make($request->password);
        $user = User::create($userValues);

        return response()->json([
            'success' => true,
            'message' => 'Register Success',
            'data' => $user
        ]);
    }

    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(AuthRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ( !$user || !Hash::check($request->password, $user->password) ) {
            return response()->json([
                'success' => false,
                'message' => 'Login Failed!',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login Success!',
            'data'    => $user,
            'token'   => $user->createToken('authToken')->accessToken
        ]);
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(AuthRequest $request)
    {
        $removeToken = $request->user()->tokens()->delete();

        if($removeToken) {
            return response()->json([
                'success' => true,
                'message' => 'Logout Success!',
            ]);
        }
    }
}
