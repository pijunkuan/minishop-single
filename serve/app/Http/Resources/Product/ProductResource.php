<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->product_images()->orderBy('sort', 'asc')->first();
        $img_url = null;
        if ($image) {
            $img_url = $image->image->url;
        }
        $price = $this->variants()->orderBy("price", "asc")->first()->value('price');
        $quantity = $this->variants()->sum('quantity');
        return [
            "id"=>$this->id,
            "product_title" => $this->product_title,
            "img" => $img_url,
            "price" => $price,
            "quantity"=>$quantity,
            "on_sale"=>$this->on_sale
        ];
    }
}
