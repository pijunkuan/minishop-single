<?php

namespace App\Http\Middleware;

use App\Http\Response\jsonResponse;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Exceptions\HttpResponseException;

class Authenticate extends Middleware
{
    use jsonResponse;
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            throw (new HttpResponseException($this->jsonErrorResponse(401,"认证失败/过期")));
//            return route('login');
        }
    }
}
