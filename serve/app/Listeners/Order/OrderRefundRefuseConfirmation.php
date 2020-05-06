<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderRefundRefuseEvent;
use App\Models\Order;
use App\Models\OrderRefund;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderRefundRefuseConfirmation
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
     * @param OrderRefundRefuseEvent $event
     * @return void
     */
    public function handle(OrderRefundRefuseEvent $event)
    {
        $order = $event->order;
        if (!in_array($order->refund_status, [
            Order::REFUND_STATUS_REFUNDING,
        ])) {
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => "该订单没有申请退款",
                "data" => null,
            ], 422)));
        }

        DB::beginTransaction();
        try {
            if ($refund = $order->refunds()->where('status', OrderRefund::REFUND_STATUS_REFUNDING)->first()) {
                $refund->status = OrderRefund::REFUND_STATUS_REFUSE;
                $reason = $event->reason ?: "空";
                $refund->reason .= "【商家拒绝理由】：{$reason}";
                $refund->save();
            }
            $order->update([
                "refund_status" => null ,
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
