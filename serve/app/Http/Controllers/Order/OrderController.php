<?php

namespace App\Http\Controllers\Order;

use App\Events\Order\OrderCancelEvent;
use App\Events\Order\OrderRefundCancelEvent;
use App\Events\Order\OrderRefundEvent;
use App\Events\Order\OrderSuccessEvent;
use App\Events\Pay\PayCreateEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderCalcRequest;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Http\Requests\Pay\PayCreateRequest;
use App\Http\Resources\Order\OrderCalcResource;
use App\Http\Resources\Order\OrderCollection;
use App\Http\Resources\Order\OrderDetail;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderPayment;
use App\Services\Order\OrderStore;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = auth('customers')->user()->orders();
        if($request->get('name')){
            $orders_id = OrderAddress::where('name','like',"%{$request->get('name')}%")->pluck('order_id');
            $orders = $orders->whereIn('id',$orders_id);
        }
        if($request->get('no')){
            $orders = $orders->where('no','like',"%{$request->get('no')}%");
        }
        if($status = $request->get('status')){
            switch($status){
                //待付款
                case Order::ORDER_STATUS_PENDING:
                    $orders = $orders->where('status',Order::ORDER_STATUS_PENDING);
                    break;
                //待发货
                case Order::ORDER_STATUS_PROCESSING:
                    $orders = $orders->where('status',Order::ORDER_STATUS_PROCESSING)->where('refund_status',null);
                    break;
                //已发货
                case Order::ORDER_STATUS_PARTIAL:
                case Order::ORDER_STATUS_SENT:
                    $orders = $orders->whereIn('status',[Order::ORDER_STATUS_PARTIAL,Order::ORDER_STATUS_SENT])->where('refund_status',null);
                    break;
                //退款中
                case Order::REFUND_STATUS_REFUNDING:
                    $orders = $orders->where('refund_status',Order::REFUND_STATUS_REFUNDING);
                    break;
            }
        }
        $orders = $orders->orderBy('created_at','desc')->paginate($request->get('pageSize'));
        return $this->jsonSuccessResponse(new OrderCollection($orders));
    }

    public function show($order)
    {
        $order = auth('customers')->user()->orders()->where('no',$order)->first();
        if(!$order) return $this->jsonErrorResponse(404,"未找到此订单");
        return $this->jsonSuccessResponse(new OrderDetail($order));
    }

    public function store(OrderStoreRequest $request)
    {
        $customer = auth('customers')->user();
        $order = OrderStore::store($customer,$request->get('address'),$request->get('items'),$request->get('remark'));
        $variant_ids = collect($request->get('items'))->pluck('variant_id');
        $customer->cartItems()->whereIn("variant_id",$variant_ids)->delete();
        return $this->jsonSuccessResponse(new OrderResource($order));
    }

    public function update($order,Request $request)
    {
        $order = auth('customers')->user()->orders()->where('no',$order)->first();
        if(!$order) return $this->jsonErrorResponse(404,"未找到此订单");
        if($request->has("status")){
            switch($request->get('status')){
                case "cancel":
                    event(new OrderCancelEvent($order,"用户主动取消订单"));
                    break;
                case "success":
                    event(new OrderSuccessEvent($order));
                    break;
                case "refunding":
                    event(new OrderRefundEvent($order,$request->get('reason')));
                    break;
                case "refund_cancel":
                    event(new OrderRefundCancelEvent($order));
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

    public function calc(OrderCalcRequest $request)
    {
        $order = OrderStore::order_calc($request->get('address'),$request->get('items'));
        return $this->jsonSuccessResponse(new OrderCalcResource($order));
    }

    public function pay_create($order,$payment)
    {
        $order = auth('customers')->user()->orders()->where('no',$order)->first();
        if(!$order) return $this->jsonErrorResponse(404,"未找到此订单");
        if($order->status !== Order::ORDER_STATUS_PENDING) return $this->jsonErrorResponse(404,"该状态无法支付");
        switch($payment){
            case "wallet":
                event(new PayCreateEvent($order,"wallet"));
                return $this->jsonSuccessResponse($order->payments()->where('status',OrderPayment::PAYMENT_STATUS_PENDING)->orderBy('created_at','desc')->first());
                break;
            default:
                return $this->jsonErrorResponse(404,"无此状态码");
                break;
        }
    }
}
