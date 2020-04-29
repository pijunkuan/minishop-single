<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $items = array();
        foreach($this->items as $value){
            $ori_variant = ProductVariant::find($value['variant_id']);
            $img_url = null;
            if ($ori_variant) {
                if ($img = $ori_variant->product->product_images()->orderBy('sort', 'asc')->first()) $img_url = $img->image->url;
            }
            $items[] = [
                "product_title" => $value['product_title'],
                "variant_title" => $value['variant_title'],
                "price" => $value['price'],
                "img_url" => $img_url,
                "product_unit" => $value['product_unit'],
                "quantity" => $value['quantity'],
                "total" => $value['quantity']*$value['price']
            ];
        }
        $address = [
            "name" => $this->address->name,
            "mobile" => $this->address->mobile,
            "address" => "{$this->address->province} {$this->address->city} {$this->address->district} {$this->address->detail}",
            "zip"=>$this->address->zip,
        ];

        return [
            "address"=>$address,
            "items"=>$items,
            "items_amount"=>$this->items_amount,
            "shipments_amount"=>$this->shipments_amount,
            "discounts_amount"=>$this->discounts_amount,
            "amount"=>$this->amount,
            "closed_reason"=>$this->closed_reason,
            "refund_reason"=>$this->refund_reason,
            "remark"=>$this->remark,
            "status"=>$this->status,
            "status_value"=>Order::orderStatusMap[$this->status],
            "send_at"=>is_null($this->send_at)?null:$this->send_at,
            "pay_at"=>is_null($this->pay_at)?null:$this->pay_at,
            "refund_at"=>is_null($this->refund_at)?null:$this->refund_at,
            "success_at"=>is_null($this->success_at)?null:$this->success_at,
            "created_at"=>is_null($this->created_at)?null:$this->created_at->toDateTimeString(),
            "updated_at"=>is_null($this->updated_at)?null:$this->updated_at->toDateTimeString(),
            "closed_at"=>is_null($this->closed_at)?null:$this->closed_at,
        ];
    }
}
