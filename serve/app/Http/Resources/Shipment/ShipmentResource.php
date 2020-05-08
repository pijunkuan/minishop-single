<?php

namespace App\Http\Resources\Shipment;

use App\Models\Shipment;
use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this['id'],
            "shipment_title" => $this['shipment_title'],
            "visibility" => $this['visibility'],
            "cost_type" => Shipment::shipmentCostMap[$this['cost_type']],
            "cost_type_code" => $this['cost_type'],
            "need_cost" => $this['need_cost'],
            "price_1" => $this['price_1'],
            "value_1" => $this['value_1'],
            "price_2" => $this['price_2'],
            "value_2" => $this['value_2'],
        ];
    }
}
