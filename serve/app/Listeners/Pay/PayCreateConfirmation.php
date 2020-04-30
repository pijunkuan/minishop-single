<?php

namespace App\Listeners\Pay;

use App\Events\Pay\PayCreateEvent;
use App\Models\OrderPayment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class PayCreateConfirmation
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
     * @param PayCreateEvent $event
     * @return void
     */
    public function handle(PayCreateEvent $event)
    {
        $order = $event->order;
        $payment = $event->payment;
        DB::beginTransaction();
        try {
            $order->payments()->update(['payment_method' => OrderPayment::PAYMENT_STATUS_CLOSED]);
            $order->payments()->create([
                "pay_amount" => $order->amount,
                "payment_method" => $payment,
                "status" => OrderPayment::PAYMENT_STATUS_PENDING,
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
