<?php

namespace App\Http\Resources\Order;

use App\Models\OrderPayment;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderPaymentResource extends JsonResource
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
            "pay_no"=>$this['pay_no'],
            "no"=>$this['no'],
            "payment_method"=>$this['payment_method'],
            "pay_amount"=>$this['pay_amount'],
            "status"=>$this['status'],
            "status_value"=>OrderPayment::paymentStatusMap[$this['status']],
            "pay_at"=>$this['pay_at'],
            "created_at"=>$this['created_at']->toDateTimeString(),
        ];
    }
}
