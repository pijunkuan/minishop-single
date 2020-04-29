<?php

namespace App\Http\Resources\Cart;

use App\Models\ProductVariant;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $ori_variant = ProductVariant::find($this->variant_id);
        if (!$ori_variant) {
            $variant = [
                "id" => $this->id,
                "variant_id" => $this->variant_id,
                "product_title" => $this->product_title,
                "variant_title" => $this->variant_title,
                "price"=>$this->price,
                "quantity"=>$this->quantity,
                "visibility"=>false,
                "reason"=>"商品不存在"
            ];
        }elseif(!$ori_variant->product->on_sale){
            $variant = [
                "id" => $this->id,
                "variant_id" => $this->variant_id,
                "product_title" => $this->product_title,
                "variant_title" => $this->variant_title,
                "price"=>$this->price,
                "quantity"=>$this->quantity,
                "visibility"=>false,
                "reason"=>"商品已下架"
            ];
        }else{
            if($ori_variant->quantity < $this->quantity){
                $variant = [
                    "id" => $this->id,
                    "variant_id" => $this->variant_id,
                    "product_title" => $ori_variant->product->product_title,
                    "variant_title" => $ori_variant->variant_title,
                    "price"=>$ori_variant->price,
                    "quantity"=>$this->quantity,
                    "visibility"=>false,
                    "reason"=>"库存不足"
                ];
            }else{
                $variant = [
                    "id" => $this->id,
                    "variant_id" => $this->variant_id,
                    "product_title" => $ori_variant->product->product_title,
                    "variant_title" => $ori_variant->variant_title,
                    "price"=>$ori_variant->price,
                    "quantity"=>$this->quantity,
                    "visibility"=>true,
                    "reason"=>null
                ];
            }

        }
        return $variant;
    }
}
