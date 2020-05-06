<?php

namespace App\Listeners\Order;

use App\Events\Order\OrderCancelEvent;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class OrderCancelConfirmation
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
     * @param  OrderCancelEvent  $event
     * @return void
     */
    public function handle(OrderCancelEvent $event)
    {
        $order = $event->order;
        if($order->status !== Order::ORDER_STATUS_PENDING)
            throw (new HttpResponseException(response()->json([
                'code'=>422,
                "msg"=>"该订单状态下无法取消订单",
                "data"=>null,
            ],422)));
        DB::beginTransaction();
        try{
            $order->status = Order::ORDER_STATUS_CANCEL;
            $order->closed_reason = $event->reason;
            $order->closed_at = now();
            $order->save();
            foreach($order->items as $item){
                $variant = ProductVariant::find($item['variant_id']);
                if($variant) $variant->addStock($item['quantity']);
            }
            DB::commit();
        }catch(\Exception $exception){
            DB::rollBack();
            throw (new HttpResponseException(response()->json([
                'code'=>422,
                "msg"=>$exception->getMessage(),
                "data"=>null,
            ],422)));
        }

    }
}
