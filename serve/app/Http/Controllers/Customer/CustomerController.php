<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\Edit;
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
            return $this->jsonSuccessResponse($this->respondWithToken(auth('customers')->login($customer)),"注册成功");
        }else{
            return $this->jsonErrorResponse(401,"创建失败");
        }
    }

    public function login(Login $request)
    {
        $credentials = \request(['mobile','password']);
        if (!$token = auth('customers')->attempt($credentials)) {
            return $this->jsonErrorResponse(401,"用户认证失败（如：密码错误）");
        }
        return $this->jsonSuccessResponse($this->respondWithToken($token));
    }

    public function update(Edit $request)
    {
        $customer = auth('customers')->user();
        if($request->get('username'))
            $customer->username = $request->get('username');
        if($request->get('password'))
            $customer->password = Hash::make($request->get('password'));
        $customer->save();
        return $this->jsonSuccessResponse($this->respondWithToken(auth('customers')->refresh()),"更新成功");

    }

    public function me()
    {
        return $this->jsonSuccessResponse(auth('customers')->user());
    }

    public function refresh()
    {
        return $this->jsonSuccessResponse($this->respondWithToken(auth('customers')->refresh()));
    }

    public function logout()
    {
        auth('customers')->logout();
        return $this->jsonSuccessResponse(null,"成功登出");
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('customers')->factory()->getTTL() * 60,
        ];
    }
}
