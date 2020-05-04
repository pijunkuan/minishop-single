<?php

namespace App\Http\Resources\Order;

use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderCalcResource extends JsonResource
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
        foreach ($this['items'] as $value) {
            $ori_variant = ProductVariant::find($value['variant_id']);
            $img_url = null;
            if ($ori_variant) {
                if ($img = $ori_variant->product->product_images()->orderBy('sort', 'asc')->first()) $img_url = $img->image->url;
            }
            $items[] = [
                "variant_id"=>$value['variant_id'],
                "product_title" => $value['product_title'],
                "variant_title" => $value['variant_title'],
                "price" => $value['price'],
                "img_url" => $img_url,
                "product_unit" => $value['product_unit'],
                "quantity" => $value['quantity'],
                "total" => $value['quantity'] * $value['price']
            ];
        }
        return [
            'address'=>$this['address'],
            'items'=>$items,
            'items_amount'=>$this['items_amount'],
            'shipments_amount'=>$this['shipments_amount'],
            'discounts_amount'=>$this['discounts_amount'],
            'amount'=>$this['amount'],
        ];
    }
}
