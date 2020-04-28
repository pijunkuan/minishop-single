<?php

namespace App\Http\Requests\Customer;


use App\Http\Requests\FormRequest;

class Edit extends FormRequest
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
                'nullable',
                "string",
                'unique:customers,username'
            ],
            "password"=>[
                'nullable',
                'alpha_dash',
                'min:6',
                "confirmed"
            ]
        ];
    }
}
