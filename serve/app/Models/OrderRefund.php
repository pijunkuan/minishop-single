<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    public $table = "order_refunds";
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }
}
