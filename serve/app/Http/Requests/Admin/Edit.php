<?php

namespace App\Http\Requests\Admin;


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
            "password"=>[
                'required',
                'alpha_dash',
                'min:6',
                "confirmed"
            ]
        ];
    }
}
