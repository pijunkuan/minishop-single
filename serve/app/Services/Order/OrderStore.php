<?php


namespace App\Services\Order;


use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\Shipment;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class OrderStore
{
    static function order_calc($address = null, $items)
    {
        if ($address) {
            $address = self::address($address);
        }
        $order = self::order_init();
        $items = self::items($items);

        $order['address'] = $address;
        $order['items'] = $items;
        $order['items_amount'] = self::items_calc($items);
        $order['shipments_amount'] = self::shipment_calc($items);
        $order['amount'] = $order['items_amount'] + $order['shipments_amount'] - $order['discounts_amount'];
        return $order;
    }

    static function store(Customer $customer, $address, $items, $remark = null)
    {
        DB::beginTransaction();
        try {
            $address = self::address($address);
            $order_init = self::order_init();
            $items = self::items($items);
            $order = $customer->orders()->create(
                array_merge($order_init, [
                    "status" => Order::ORDER_STATUS_PENDING,
                    "remark" => $remark
                ])
            );
            $order->address()->create($address);
            foreach ($items as $item) {
                $order->items()->create($item);
                if (ProductVariant::find($item['variant_id'])->decreaseStock($item['quantity']) <= 0) {
                    throw (new HttpResponseException(response()->json([
                        'code' => 422,
                        "msg" => "该商品库存不足",
                        "data" => null,
                    ], 422)));
                }
            }

            $order->items_amount = self::items_calc($items);

            $order->shipments_amount = self::shipment_calc($items);

            $order->amount = $order->items_amount + $order->shipments_amount - $order->discounts_amount;
            $order->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw (new HttpResponseException(response()->json([
                'code' => 422,
                "msg" => $exception->getMessage(),
                "data" => null,
            ], 422)));
        }
        return $order;
    }



    private static function items($items)
    {
        $variants = array();
        foreach ($items as $item) {
            $variant = ProductVariant::find($item['variant_id']);
            $variants[] = [
                "variant_id" => $variant['id'],
                "variant_code" => $variant['variant_code'],
                "product_unit" => $variant->product['product_unit'],
                "product_title" => $variant->product['product_title'],
                "variant_title" => $variant['variant_title'],
                "price" => $variant['price'],
                "quantity" => $item['quantity'],
                "weight" => $variant['weight'],
            ];
        }
        return $variants;
    }

    private static function items_calc($items)
    {
        $items_amount = 0;
        foreach ($items as $item) {
            $items_amount += $item['price'] * $item['quantity'];
        }
        return $items_amount;
    }

    private static function address($address)
    {
        return [
            'city' => isset($address['city']) ? $address['city'] : "",
            'district' => isset($address['district']) ? $address['district'] : "",
            'province' => isset($address['province']) ? $address['province'] : "",
            'detail' => isset($address['detail']) ? $address['detail'] : "",
            'zip' => isset($address['zip']) ? $address['zip'] : "",
            'name' => isset($address['name']) ? $address['name'] : "",
            'mobile' => isset($address['mobile']) ? $address['mobile'] : ""
        ];
    }

    private static function order_init()
    {
        return [
            "items_amount" => 0,
            "shipments_amount" => 0,
            "discounts_amount" => 0,
            "amount" => 0,
        ];
    }

    private static function shipment_calc($items)
    {
        $shipment = Shipment::where('visibility', 1)->first();
        $shipments_amount = 0;
        if ($shipment) {
            if ($shipment->need_cost) {
                $items_weight = 0;
                $items_amount = 0;
                foreach ($items as $item) {
                    $items_amount += $item['quantity'];
                    $items_weight += $item['quantity'] * $item['weight'];
                }

                switch ($shipment->cost_type) {
                    case Shipment::SHIPMENT_COST_NUMERIC:
                        $calc_value = $items_amount - $shipment->value_1;
                        $shipments_amount += $shipment->price_1 * 1.00;
                        if ($shipment->value_2 && $calc_value) {
                            $shipments_amount += ceil($calc_value / $shipment->value_2) * $shipment->price_2;
                        }
                        break;
                    case Shipment::SHIPMENT_COST_WEIGHT:
                        $calc_value = $items_weight - $shipment->value_1;
                        $shipments_amount += $shipment->price_1 * 1.00;
                        if ($shipment->value_2 && $calc_value) {
                            $shipments_amount += ceil($calc_value / $shipment->value_2) * $shipment->price_2;
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        return $shipments_amount;

    }


}
