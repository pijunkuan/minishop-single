<?php

namespace App\Http\Requests\Address;


use App\Http\Requests\FormRequest;

class AddressStoreRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name"=>"required",
            "province"=>"required",
            "district"=>"nullable",
            "city"=>"required",
            "detail"=>"required",
            "mobile"=>[
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/'
            ],
            "default"=>"nullable|boolean",
            "zip"=>"nullable",
        ];
    }

    public function attributes()
    {
        return [
            "name"=>"姓名",
            "province"=>"省",
            "district"=>"区",
            "city"=>"市",
            "detail"=>"地址详情",
        ];
    }
}
