<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipment extends Model
{
    public $table = "order_shipments";
    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }

    public function items()
    {
        return $this->hasMany(OrderShipmentItem::class, "shipment_id");
    }
}
