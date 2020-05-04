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
        $img_url = null;
        if($ori_variant){
            if($img = $ori_variant->product->product_images()->orderBy('sort','asc')->first()) $img_url = $img->image->url;
        }
        if (!$ori_variant) {
            $variant = [
                "id" => $this->id,
                "variant_id" => $this->variant_id,
                "product_title" => $this->product_title,
                "variant_title" => $this->variant_title,
                "price"=>$this->price,
                "quantity"=>$this->quantity,
                "stock"=>0,
                "visibility"=>false,
                "reason"=>"商品不存在",
                "img_url"=>$img_url,
            ];
        }elseif(!$ori_variant->product->on_sale){
            $variant = [
                "id" => $this->id,
                "variant_id" => $this->variant_id,
                "product_title" => $this->product_title,
                "variant_title" => $this->variant_title,
                "price"=>$this->price,
                "quantity"=>$this->quantity,
                "stock"=>0,
                "visibility"=>false,
                "reason"=>"商品已下架",
                "img_url"=>$img_url,

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
                    "stock"=>$ori_variant->quantity,
                    "visibility"=>false,
                    "reason"=>"库存不足",
                    "img_url"=>$img_url,
                ];
            }else{
                $variant = [
                    "id" => $this->id,
                    "variant_id" => $this->variant_id,
                    "product_title" => $ori_variant->product->product_title,
                    "variant_title" => $ori_variant->variant_title,
                    "price"=>$ori_variant->price,
                    "quantity"=>$this->quantity,
                    "stock"=>$ori_variant->quantity,
                    "visibility"=>true,
                    "reason"=>null,
                    "img_url"=>$img_url,
                ];
            }
        }
        return $variant;
    }
}
