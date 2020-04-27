<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const ORDER_STATUS_PENDING = "pending";
    const ORDER_STATUS_PROCESSING = "processing";
    const ORDER_STATUS_PARTIAL = "partial";
    const ORDER_STATUS_SENT = "sent";
    const ORDER_STATUS_SUCCESS = "success";
    const ORDER_STATUS_CLOSED = "closed";
    const ORDER_STATUS_REFUNDING = "refunding";
    const ORDER_STATUS_REFUNDED = "refunded";

    const orderStatusMap = [
        self::ORDER_STATUS_PENDING=>"待付款",
        self::ORDER_STATUS_PROCESSING=>"已付款",
        self::ORDER_STATUS_PARTIAL=>"部分发货",
        self::ORDER_STATUS_SENT=>"全部发货",
        self::ORDER_STATUS_SUCCESS=>"已成功",
        self::ORDER_STATUS_CLOSED=>"已关闭",
        self::ORDER_STATUS_REFUNDING=>"退款中",
        self::ORDER_STATUS_REFUNDED=>"已退款",
    ];

    public $table = "orders";
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class,"customer_id");
    }

    public function address()
    {
        return $this->hasOne(OrderAddress::class,"order_id");
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class,"order_id");
    }

    public function payments()
    {
        return $this->hasMay(OrderPayment::class,"order_id");
    }
}
