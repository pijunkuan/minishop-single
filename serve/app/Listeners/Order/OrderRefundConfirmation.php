<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderRefundEvent;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderRefundConfirmation
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
     * @param OrderRefundEvent $event
     * @return void
     */
    public function handle(OrderRefundEvent $event)
    {
        $order = $event->order;
        if (in_array($order->status, [
                Order::ORDER_STATUS_PENDING,
                Order::ORDER_STATUS_CANCEL,
                Order::ORDER_STATUS_CLOSED,
            ]) || !is_null($order['refund_status'])) {
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => "该订单状态下无法申请退款",
                "data" => $order['refund_status'],
            ], 422)));
        }
        DB::beginTransaction();
        try {
            $order->refund_status = Order::REFUND_STATUS_REFUNDING;
            $order->save();
            $order->refunds()->create([
                "reason"=>$event->reason,
                "status"=>OrderRefund::REFUND_STATUS_REFUNDING
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
