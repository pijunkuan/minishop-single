<?php

namespace App\Events\Pay;

use App\Models\Order;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PayCreateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order,$payment;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param $payment
     */
    public function __construct(Order $order,$payment)
    {
        $this->order = $order;
        $this->payment = $payment;
    }

}
