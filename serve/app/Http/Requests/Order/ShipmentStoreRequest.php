<?php

namespace App\Http\Requests\Order;


use App\Http\Requests\FormRequest;
use App\Models\Order;

class ShipmentStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        return [
            "shipment_no"=>"required",
            "shipment_company"=>"required",
            "items"=>"required|array",
            "items.*.variant_id"=>[
                function($attribute, $value, $fail){
                    $order = Order::find($this->route('order'));
                    if(!$order) return $fail('订单不存在');
                    $order_item = $order->items()->where('variant_id',$value)->first();
                    if(!$order_item) return $fail('订单不存在该商品');
                    $ship_quantity = $order->ship_items()->where('variant_id',$value)->sum('quantity');
                    preg_match('/items\.(\d+)\.variant_id/', $attribute, $m);
                    $index = $m[1];
                    // 根据索引找到用户所提交的购买数量
                    $quantity = $this->input('items')[$index]['quantity'];
                    if($quantity > $order_item->quantity*1.00 - $ship_quantity*1.00) return $fail("超出可发货数量");
                }
            ]
        ];
    }
}
