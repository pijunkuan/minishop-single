<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Login;
use App\Http\Requests\Customer\Register;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customers',['except'=>['register','login']]);
    }

    public function register(Register $request)
    {
        $customer = new Customer([
            "mobile"=>$request->get('mobile'),
            "password"=>Hash::make($request->get('password'))
        ]);
        $customer->save();
        if($customer){
            return response()->json(['msg'=>'success']);
        }else{
            return response()->json(['msg'=>'创建失败']);
        }
    }

    public function login(Login $request)
    {
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
