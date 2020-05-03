<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    public $table = "shipments";
    public $timestamps = false;
    protected $guarded = [];
    protected $casts = [
        "visibility" => "boolean",
        "need_cost" => "boolean",
    ];

    const SHIPMENT_COST_NUMERIC = "numeric";
    const SHIPMENT_COST_WEIGHT = "weight";

    const shipmentCostMap = [
        self::SHIPMENT_COST_NUMERIC => "按件数",
        self::SHIPMENT_COST_WEIGHT => "按重量"
    ];


}
