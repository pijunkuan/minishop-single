<?php

namespace App\Http\Requests\Cart;


use App\Http\Requests\FormRequest;
use App\Models\ProductVariant;

class AddCartRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'variant_id'=>[
                'required',
                function($attribute,$value,$fail){
                    if(!$variant = ProductVariant::find($value))
                        return $fail('该商品不存在');
                    if(!$variant->product->on_sale )
                        return $fail('该商品未上架');
//                    if($variant->quantity <= 0 )
//                        return $fail('该商品已售空');
//                    if ($variant->quantity < $this->get('amount'))
//                        return $fail('该商品库存不足');
                },
            ],
            'quantity'=>['required','integer','min:1'],
        ];
    }

    public function attributes()
    {
        return [
            "amount" => "商品数量"
        ];
    }

    public function messages()
    {
        return [
            'variant_id.required'=>"请选择商品"
        ];
    }
}
