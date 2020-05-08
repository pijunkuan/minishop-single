<?php

namespace App\Http\Controllers\Shipment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shipment\ShipmentRequest;
use App\Http\Resources\Shipment\ShipmentResource;
use App\Models\Shipment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminShipmentController extends Controller
{
    public function index()
    {
        return $this->jsonSuccessResponse(ShipmentResource::collection(Shipment::get()));
    }

    public function store(ShipmentRequest $request)
    {
        $shipment = $this->shipment($request);
        if (Shipment::count() > 5) return $this->jsonErrorResponse(405, "超过最大运费模板设置范围（5）");
        DB::beginTransaction();
        try {
            if ($shipment['visibility']) {
                Shipment::where('visibility', 1)->update(['visibility' => false]);
            }
            $shipment = Shipment::create($shipment);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->jsonErrorResponse(404, $exception->getMessage());
        }

        return $this->jsonSuccessResponse($shipment);
    }

    public function show(Shipment $shipment)
    {
        return $this->jsonSuccessResponse($shipment);
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return $this->jsonSuccessResponse();
    }

    public function update(Shipment $shipment, Request $request)
    {
        $update = $this->shipment($request);
        DB::beginTransaction();
        try {
            if ($update['visibility']) {
                Shipment::where('visibility', 1)->update(['visibility' => false]);
            }
            $shipment->update($update);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->jsonErrorResponse(404, $exception->getMessage());
        }
        return $this->jsonSuccessResponse($shipment);
    }

    private function shipment(Request $request)
    {
        $shipment=array();
        if ($request->get('shipment_title')) $shipment['shipment_title'] = $request->get('shipment_title');
        if ($request->has('visibility')) $shipment['visibility'] = $request->get('visibility');
        if ($request->has('need_cost')) $shipment['need_cost'] = $request->get('need_cost');
        if (isset($shipment['need_cost'])) {
            if ($shipment['need_cost']) {
                if (!in_array($request->get('cost_type'), [Shipment::SHIPMENT_COST_WEIGHT, Shipment::SHIPMENT_COST_NUMERIC])) {
                    throw new HttpResponseException($this->jsonErrorResponse(405, "计价类型不在范围内"));
                }
                $shipment['cost_type'] = $request->get('cost_type');
                $shipment['price_1'] = $request->get('price_1');
                $shipment['value_1'] = $request->get('value_1');
                $shipment['price_2'] = $request->get('price_2');
                $shipment['value_2'] = $request->get('value_2');
            }
        }
        return $shipment;
    }
}
