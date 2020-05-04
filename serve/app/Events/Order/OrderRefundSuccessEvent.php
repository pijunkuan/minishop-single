<?php

namespace App\Events\Order;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderRefundSuccessEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order, $reason;


    /**
     * Create a new event instance.
     *
     * @param Order $order
     * @param $reason
     */
    public function __construct(Order $order, $reason=null)
    {
        $this->order = $order;
        $this->reason = $reason;
    }


}
