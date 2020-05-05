<?php

namespace App\Http\Requests\Wallet;


use App\Http\Requests\FormRequest;
use App\Models\Wallet;

class WalletStoreRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "amount" => "required|numeric",
            "type" => [
                "required",
                function ($attributes, $value, $fail) {
                    if(!in_array($value,[Wallet::WALLET_IN,Wallet::WALLET_OUT]))
                        return $fail("非法类型（in 收入/out 支出）");
                }
            ]
        ];
    }
}
