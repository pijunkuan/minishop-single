<?php

namespace App\Http\Resources\Address;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "province"=>$this->province,
            "city"=>$this->city,
            "district"=>$this->district,
            "detail"=>$this->detail,
            "full_address"=>"{$this->province} {$this->city} {$this->district} {$this->detail} ",
            "zip"=>$this->zip,
            "mobile"=>$this->mobile,
            "default"=>$this->default,
        ];
    }
}
