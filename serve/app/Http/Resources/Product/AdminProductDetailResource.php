<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminProductDetailResource extends JsonResource
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
       return [
           "product"=>[
               "product_title"=>$this->product_title,
               "product_des"=>$this->product_des,
               "product_unit"=>$this->product_unit,
               "on_sale"=>$this->on_sale,
           ],
           "categories"=>$this->categories,
           "images"=>$images_array,
           "variants"=>$this->variants,
           "content"=>$this->content->content,
           "created_at"=>$this->created_at->toDateTimeString(),
           "updated_at"=>$this->updated_at->toDateTimeString(),

       ];
    }
}
