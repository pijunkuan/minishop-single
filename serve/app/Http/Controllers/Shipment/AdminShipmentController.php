<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipment\ShipmentRequest;
use App\Models\Shipment;
use Illuminate\Http\Request;

class AdminShipmentController extends Controller
{
    public function index()
    {

    }

    public function store(ShipmentRequest $request)
    {
        $shipment = [
          "shipment_title"=>$request->get('shipment_title'),
          "visibility"=>$request->get('visibility'),
          "need_cost"=>$request->get('need_cost'),
        ];
        if($shipment['need_cost']){
            if(!in_array($request->get('cost_type'),[Shipment::SHIPMENT_COST_WEIGHT,Shipment::SHIPMENT_COST_NUMERIC])){
                return $this->jsonErrorResponse(404,"计价类型不在范围内");
            }
            $shipment['cost_type'] = $request->get('cost_type');
//            if($request->get('price_1'))
        }
    }

    public function show(Request $request)
    {

    }

    public function destroy(Shipment $shipment)
    {

    }

    public function update(Shipment $shipment)
    {

    }
}
