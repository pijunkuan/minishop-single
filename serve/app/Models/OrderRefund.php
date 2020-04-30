<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRefund extends Model
{
    public $table = "order_refunds";
    protected $guarded = [];

    const REFUND_STATUS_REFUNDING = "refunding";
    const REFUND_STATUS_REFUNDED = "refunded";
    const REFUND_STATUS_CANCEL = "cancel";
    const REFUND_STATUS_REFUSE = "refuse";

    const refundStatusMap = [
      self::REFUND_STATUS_REFUNDING => "退款中",
      self::REFUND_STATUS_REFUNDED => "已退款",
      self::REFUND_STATUS_CANCEL => "取消",
      self::REFUND_STATUS_REFUSE => "拒绝",
    ];


    public function order()
    {
        return $this->belongsTo(Order::class, "order_id");
    }
}
