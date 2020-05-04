<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderRefundCancelEvent;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class OrderRefundCancelConfirmation
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
     * @param  OrderRefundCancelEvent  $event
     * @return void
     */
    public function handle(OrderRefundCancelEvent $event)
    {
        $order = $event->order;
        if (!in_array($order->refund_status, [
            Order::REFUND_STATUS_REFUNDING,
        ])) {
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => "该订单没有取消退款",
                "data" => null,
            ], 422)));
        }

        DB::beginTransaction();
        try {
            if($refund = $order->refunds()->where('status',OrderRefund::REFUND_STATUS_REFUNDING)->first()){
                $refund->status = OrderRefund::REFUND_STATUS_CANCEL;
                $refund->reason .= "【客户主动取消退款】";
                $refund->save();
            }
            $order->update([
                "refund_status"=> null
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
