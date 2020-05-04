<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetail extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = $this->product_images()->orderBy('sort','asc')->get();
        $images_array = array();
        foreach($images as $key => $image){
            $images_array[] = [
                "image_id"=>$image['image_id'],
                "url"=>$image->image->url,
                "sort"=>$image['sort']
            ];
        }
        foreach($this->variants as $value){
            $variants[] = [
                "id"=>$value['id'],
                "variant_title"=>$value['variant_title'],
                "variant_code"=>$value['variant_code'],
                "stock"=>$value['quantity'],
                "ori_price"=>$value['ori_price'],
                "price"=>$value['price'],
                "weight"=>$value['weight'],
            ];
        }
        return [
            "product"=>[
                "product_title"=>$this->product_title,
                "product_des"=>$this->product_des,
                "product_unit"=>$this->product_unit,
                "on_sale"=>$this->on_sale,
            ],
            "images"=>$images_array,
            "variants"=>$variants,
            "content"=>$this->content->content,
            "created_at"=>$this->created_at->toDateTimeString(),
            "updated_at"=>$this->updated_at->toDateTimeString(),

        ];
    }
}
