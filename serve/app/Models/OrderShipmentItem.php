<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipmentItem extends Model
{
    public $table = "order_shipment_items";
    public $timestamps = false;
    protected $guarded = [];

    public function shipment()
    {
        return $this->belongsTo(OrderShipment::class,"shipment_id");
    }
}
