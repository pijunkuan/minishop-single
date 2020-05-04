<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Edit;
use App\Http\Requests\Admin\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admins',['except'=>['login']]);
    }

    public function login(Login $request)
    {
        $credentials = \request(['username','password']);
        if (!$token = auth('admins')->attempt($credentials)) {
            return $this->jsonErrorResponse(401,"用户认证失败");
        }
        return $this->jsonSuccessResponse($this->respondWithToken($token));
    }
    public function update(Edit $request)
    {
        $customer = auth('admins')->user();
        if($request->get('password'))
            $customer->password = Hash::make($request->get('password'));
        $customer->save();
        return $this->jsonSuccessResponse($this->respondWithToken(auth('admins')->refresh()),"更新成功");

    }

    public function refresh()
    {
        return $this->jsonSuccessResponse($this->respondWithToken(auth('admins')->refresh()));
    }

    public function me()
    {
        return $this->jsonSuccessResponse(auth('admins')->user());
    }



    public function logout()
    {
        auth('admins')->logout();
        return $this->jsonSuccessResponse(null,"成功登出");
    }

    public function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth('admins')->factory()->getTTL() * 60
        ];
    }
}
