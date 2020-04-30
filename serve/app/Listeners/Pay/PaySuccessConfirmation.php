<?php

namespace App\Listeners\Pay;

use App\Events\Pay\PaySuccessEvent;
use App\Models\Order;
use App\Models\OrderPayment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class PaySuccessConfirmation
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  PaySuccessEvent  $event
     * @return void
     */
    public function handle(PaySuccessEvent $event)
    {
        DB::beginTransaction();
        try{
            $pay_at = now();
            $payment = $event->payment;
            $payment->update([
                "status"=> OrderPayment::PAYMENT_STATUS_SUCCESS,
                "pay_no"=>$event->pay_no,
                "pay_at"=>$pay_at
            ]);
            $payment->order->update([
                "status"=>Order::ORDER_STATUS_PROCESSING,
                "pay_at"=>$pay_at
            ]);
            DB::commit();
        }catch(\Exception $exception){
            DB::rollBack();
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => $exception->getMessage(),
                "data" => null,
            ], 422)));
        }
    }
}
