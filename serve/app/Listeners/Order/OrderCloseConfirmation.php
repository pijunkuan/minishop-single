<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCloseEvent;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderCloseConfirmation
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
     * @param OrderCloseEvent $event
     * @return void
     */
    public function handle(OrderCloseEvent $event)
    {
        $order = $event->order;
        if (!in_array($order->status, [
            Order::ORDER_STATUS_PENDING,
        ])) {
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => "该订单状态下无法关闭",
                "data" => null,
            ], 422)));
        }
        DB::beginTransaction();
        try {
            $order->update([
                'status' => Order::ORDER_STATUS_CLOSED,
                'closed_reason' => $event->reason,
                "closed_at" => now()
            ]);
            foreach ($order->items as $item){
                $variant = ProductVariant::find($item['variant_id']);
                if($variant)
                    $variant->addStock($item['quantity']);
            }
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
