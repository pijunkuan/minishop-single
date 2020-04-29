<?php

namespace App\Http\Requests\Order;


use App\Http\Requests\FormRequest;
use App\Models\ProductVariant;

class OrderStoreRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address'=>'required|array',
            'address.detail'=>'required',
            'address.city'=>'required',
            'address.district'=>'nullable',
            'address.province'=>'required',
            'address.zip'=>'nullable',
            'address.name'=>'required',
            'address.mobile'=>[
                'required',
                'regex:/^((13[0-9])|(14[5,7])|(15[0-3,5-9])|(17[0,3,5-8])|(18[0-9])|166|198|199)\d{8}$/'
            ],
            'items'  => ['required', 'array'],
            'items.*.variant_id' => [
                // 检查 items 数组下每一个子数组的 variant_id 参数
                'required',
                function ($attribute, $value, $fail) {
                    if (!$sku = ProductVariant::find($value)) return $fail('该商品不存在');
                    if (!$sku->product->on_sale) return $fail('该商品未上架');
                    if ($sku->quantity === 0) return $fail('该商品已售完');
                    // 获取当前索引
                    preg_match('/items\.(\d+)\.variant_id/', $attribute, $m);
                    $index = $m[1];
                    // 根据索引找到用户所提交的购买数量
                    $quantity = $this->input('items')[$index]['quantity'];
                    if ($quantity > 0 && $sku->quantity < $quantity) return $fail('该商品库存不足');
                },
            ],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function attributes()
    {
        return [
            'address'=>'地址信息',
            'address.address'=>'其他地址',
            'address.city'=>'市',
            'address.district'=>'区',
            'address.province'=>'省',
            'address.zip'=>'邮编',
            'address.name'=>'姓名',
            'address.mobile'=>'手机号',
        ];
    }
}
