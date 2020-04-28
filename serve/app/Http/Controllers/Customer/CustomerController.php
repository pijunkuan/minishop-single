<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customers',['except'=>['register','login']]);
    }

    public function register(Request $request)
    {
        return "ok";
    }

    public function login(Request $request)
    {
        $mobile = $request->get('mobile');
        $password = $request->get('password');
        $credentials = \request(['mobile','password']);
        if (!$token = auth('customers')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return response()->json(auth('customers')->user());
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('customers')->refresh());
    }

    public function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'Authorization' => "Bearer ".$token,
            'token_type' => 'Bearer',
            'expires_in' => auth('customers')->factory()->getTTL() * 60
        ]);
    }
}
