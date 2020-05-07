<?php

namespace App\Http\Resources\Customer;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            "id"=>$this['id'],
            "username"=>$this['username'],
            "mobile"=>$this['mobile'],
            "balance"=>$this->wallets()->sum('amount'),
            "created_at"=>$this['created_at']->toDateTimeString()
        ];
    }
}
