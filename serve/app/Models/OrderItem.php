<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $table = "order_items";
    protected $guarded = [];
    public $timestamps=false;

    public function order()
    {
        return $this->belongsTo(Order::class,"order_id");
    }
}
