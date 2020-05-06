<?php

namespace App\Http\Requests\Order;


use App\Http\Requests\FormRequest;

class AdminOrderUpdateRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
//            'address'=>'nullable|array',
//            'address.detail'=>'nullable',
//            'address.city'=>'nullable',
//            'address.district'=>'nullable',
//            'address.province'=>'nullable',
//            'address.zip'=>'nullable',
//            'address.name'=>'nullable',
//            'address.mobile'=>[
//                'nullable',
//                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/'
//            ],
        ];
    }
}
