<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderRefundSuccessEvent;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderRefundSuccessConfirmation
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
     * @param  OrderRefundSuccessEvent  $event
     * @return void
     */
    public function handle(OrderRefundSuccessEvent $event)
    {
        $order = $event->order;
        if (!in_array($order->status, [
            Order::ORDER_STATUS_PROCESSING,
            Order::ORDER_STATUS_PARTIAL,
            Order::ORDER_STATUS_SENT,
            Order::ORDER_STATUS_SUCCESS,
        ])) {
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => "该订单状态下无法退款",
                "data" => null,
            ], 422)));
        }

        DB::beginTransaction();
        try {
            if($refund = $order->refunds()->where('status',OrderRefund::REFUND_STATUS_REFUNDING)->first()){
                $refund->status = OrderRefund::REFUND_STATUS_REFUNDED;
                $reason = $event->reason?:"空";
                $refund->reason .= "【商家理由】：{$reason}";
                $refund->save();
            }else{
                $reason = $event->reason?:"空";
                $order->refunds()->create([
                    'status'=>OrderRefund::REFUND_STATUS_REFUNDED,
                    'reason'=>"商家主动退款，【商家理由】：{$reason}"
                ]);
            }
            $order->update([
                'status' => Order::ORDER_STATUS_CLOSED,
                "refund_status"=>Order::REFUND_STATUS_REFUNDED,
                "closed_reason"=>"退款",
                "closed_at" => now()
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => $exception->getMessage(),
                "data" => null,
            ], 422)));
        }
    }
}
