<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\ShipmentStoreRequest;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderShipmentController extends Controller
{
    public function index($order, Request $request)
    {
        $order = Order::find($order);
        if(!$order) return $this->jsonErrorResponse(404,"无此订单记录");
        return $this->jsonSuccessResponse($order->shipments);
    }

    public function store($order, ShipmentStoreRequest $request)
    {
        $order = Order::find($order);
        if(!$order) return $this->jsonErrorResponse(404,"无此订单记录");
        if(in_array($order->status,[
            Order::ORDER_STATUS_CLOSED,
            Order::ORDER_STATUS_CANCEL,
            Order::ORDER_STATUS_PENDING,
            Order::ORDER_STATUS_SUCCESS
        ])) return $this->jsonErrorResponse(404,'该订单状态无法发货');
        DB::beginTransaction();
        try{
            $shipment =$order->shipments()->create([
                "shipment_no"=>$request->get('shipment_no'),
                "shipment_company"=>$request->get('shipment_company'),
            ]);
            foreach($request->get('items') as $value){
                $temp = $order->items()->where('variant_id',$value['variant_id'])->first();
                $shipment->items()->create([
                    "variant_id"=>$temp['variant_id'],
                    "variant_code"=>$temp['variant_code'],
                    "product_title"=>$temp['product_title'],
                    "product_unit"=>$temp['product_unit'],
                    "variant_title"=>$temp['variant_title'],
                    "quantity"=>$value['quantity'],
                    "weight"=>$temp['weight'],
                ]);
            }
            if(is_null($order->send_at)) $order->send_at = $shipment->created_at->toDateTimeString();
            if($order->ship_items()->sum('quantity') == $order->items()->sum('quantity')){
                $order->status = Order::ORDER_STATUS_SENT;
            }else{
                $order->status = Order::ORDER_STATUS_PARTIAL;
            }
            $order->save();
            DB::commit();
        }catch(\Exception $exception){
            DB::rollBack();
            return $this->jsonErrorResponse(404,$exception->getMessage());
        }
        return $this->jsonSuccessResponse();
    }
}
