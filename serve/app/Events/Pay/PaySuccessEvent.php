<?php

namespace App\Events\Pay;

use App\Models\OrderPayment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaySuccessEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $payment, $pay_no;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(OrderPayment $payment, $pay_no)
    {
        $this->payment = $payment;
        $this->pay_no = $pay_no;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

}
