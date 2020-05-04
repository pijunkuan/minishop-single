<?php

namespace App\Http\Requests\Cart;


use App\Http\Requests\FormRequest;
use App\Models\ProductVariant;

class CartCacheRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'items' => ['required', 'array'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
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

        ];
    }
}
