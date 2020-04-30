<?php

namespace App\Http\Controllers\Pay;

use App\Events\Pay\PaySuccessEvent;
use App\Http\Controllers\Controller;
use App\Models\OrderPayment;
use App\Models\Wallet;

class PayController extends Controller
{


    public function wallet($no)
    {
        $payment = OrderPayment::where('no',$no)->first();
        if($payment->status !== OrderPayment::PAYMENT_STATUS_PENDING) return $this->jsonErrorResponse(404,"该状态无法支付");
        $customer = auth('customers')->user();
        if($customer->wallets()->sum('amount') < $payment->pay_amount) return $this->jsonErrorResponse(404,"余额不足");
        if($customer->id !== $payment->order->customer->id) return $this->jsonErrorResponse(404,"该订单无法用此账户支付");
        $rs =$customer->wallets()->create([
            'amount' => 0 - $payment->pay_amount*1.00,
            'type'=>Wallet::WALLET_OUT,
            "content"=>"支付编号：{$payment->no} ， 订单编号：{$payment->order->no}"
        ]);
        event(new PaySuccessEvent($payment,$rs->no));
        return $this->jsonSuccessResponse(null,"支付成功");
    }
}
