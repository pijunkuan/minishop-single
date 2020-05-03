<?php

namespace App\Http\Requests\Shipment;


use App\Http\Requests\FormRequest;

class ShipmentRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "shipment_title"=>"required",
            "visibility"=>"required|boolean",
            "need_cost"=>"required|boolean",
            "cost_type"=>"nullable",
            "price_1"=>"nullable|numeric",
            "value_1"=>"nullable|numeric",
            "price_2"=>"nullable|numeric",
            "value_2"=>"nullable|numeric",
        ];
    }
}
