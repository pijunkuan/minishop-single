<?php

namespace App\Http\Resources\Order;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $address = $this->address;
        $items = array();
        foreach ($this->items as $value) {
            $ori_variant = ProductVariant::find($value['variant_id']);
            $img_url = null;
            if ($ori_variant) {
                if ($img = $ori_variant->product->product_images()->orderBy('sort', 'asc')->first()) $img_url = $img->image->url;
            }
            $items[] = [
                "product_title" => $value['product_title'],
                "variant_id" => $value['variant_id'],
                "variant_title" => $value['variant_title'],
                "price" => $value['price'],
                "img_url" => $img_url,
                "product_unit" => $value['product_unit'],
                "quantity" => $value['quantity'],
                "total" => $value['quantity']*$value['price']
            ];
        }
        return [
            "id" => $this->id,
            "no" => $this->no,
            "name" => $address->name,
            "mobile" => $address->mobile,
            "address" => "{$address->province} {$address->city} {$address->district} {$address->detail}",
            "zip"=>$address->zip,
            "status"=>$this->refund_status?$this->refund_status:$this->status,
            "status_value"=>$this->refund_status?Order::refundStatusMap[$this->refund_status]:Order::orderStatusMap[$this->status],
            "amount"=>$this->amount,
            "items" => $items,
            "remark" => $this['remark'],
            "created_at" => $this['created_at']->toDateTimeString(),
        ];
    }
}
