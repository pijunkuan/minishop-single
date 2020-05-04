<?php

namespace App\Http\Resources\Order;

use App\Models\OrderPayment;
use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderShipmentResource extends JsonResource
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
            "shipment_no"=>$this['shipment_no'],
            "shipment_company"=>$this['shipment_company'],
            "items"=>$items,
            "created_at"=>$this['created_at']->toDateTimeString()
        ];
    }
}
