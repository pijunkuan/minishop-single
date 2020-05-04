<?php

namespace App\Http\Controllers\Order;

use App\Events\Order\OrderCancelEvent;
use App\Events\Order\OrderCloseEvent;
use App\Events\Order\OrderRefundEvent;
use App\Events\Order\OrderRefundRefuseEvent;
use App\Events\Order\OrderRefundSuccessEvent;
use App\Events\Order\OrderSuccessEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\Order\AdminOrderDetail;
use App\Http\Resources\Order\OrderCollection;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = new Order();
        $orders = $orders->orderBy('created_at', 'desc');
        $orders = $orders->paginate($request->get('pageSize'));
        return $this->jsonSuccessResponse(new OrderCollection($orders));
    }

    public function show(Order $order)
    {
        return $this->jsonSuccessResponse(new AdminOrderDetail($order));
    }

    public function update(Order $order,Request $request)
    {
        if($request->has("status")){
            switch($request->get('status')){
                case "closed":
                    event(new OrderCloseEvent($order,"商家关闭订单"));
                    break;
                case "refunded":
                    event(new OrderRefundSuccessEvent($order,$request->get('reason')));
                    break;
                case "refund_refuse":
                    event(new OrderRefundRefuseEvent($order,$request->get('reason')));
                    break;
                default:
                    return $this->jsonErrorResponse(404,"无此状态码");
                    break;
            }
            return $this->jsonSuccessResponse();
        }else{
            return $this->jsonErrorResponse(404,"状态必须填写");
        }
    }
}
