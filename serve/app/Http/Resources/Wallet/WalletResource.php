<?php

namespace App\Http\Resources\Wallet;

use App\Models\Wallet;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this['id'],
            'no'=>$this['no'],
            "amount" => $this['amount'],
            "type_code" => $this['type'],
            "type" => Wallet::walletTypeMap[$this['type']],
            "content" => $this['content'],
            "created_at" => $this['created_at']->toDateTimeString(),
            "updated_at" => $this['updated_at']->toDateTimeString(),
        ];
    }
}
