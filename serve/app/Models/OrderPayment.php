<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    public $table = "order_payments";
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class,"order_id");
    }
}
