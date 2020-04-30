<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderSuccessEvent;
use App\Models\Order;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderSuccessConfirmation
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
     * @param OrderSuccessEvent $event
     * @return void
     */
    public function handle(OrderSuccessEvent $event)
    {
        $order = $event->order;
        if (in_array($order->status, [
            Order::ORDER_STATUS_PENDING,
            Order::ORDER_STATUS_CANCEL,
            Order::ORDER_STATUS_CLOSED,
            Order::ORDER_STATUS_SUCCESS,
        ])) {
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => "该订单状态下无法完成订单",
                "data" => null,
            ], 422)));
        }
        DB::beginTransaction();
        try {
            $order->status = Order::ORDER_STATUS_SUCCESS;
            $order->success_at = now();
            $order->save();
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
