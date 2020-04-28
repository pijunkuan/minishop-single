<?php

namespace App\Http\Requests\Admin;


use App\Http\Requests\FormRequest;

class Login extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "username"=>[
                'required',
                "string",
                'exists:admins,username'
            ],
            "password"=>[
                'required',
                'alpha_dash',
                'min:6'
            ]
        ];
    }
}
