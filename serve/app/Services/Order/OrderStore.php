<?php


namespace App\Services\Order;


use App\Models\Customer;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class OrderStore
{
    static function store(Customer $customer, $address, $items, $remark = null)
    {
        DB::beginTransaction();
        try {
            $address = [
                'city' => $address['city'],
                'district' => isset($address['district']) ? $address['district'] : "",
                'province' => $address['province'],
                'detail' => $address['detail'],
                'zip' => isset($address['zip']) ? $address['zip'] : "",
                'name' => $address['name'],
                'mobile' => $address['mobile']
            ];
            $order = $customer->orders()->create([
                "items_amount" => 0,
                "shipments_amount" => 0,
                "discounts_amount" => 0,
                "amount" => 0,
                "status" => Order::ORDER_STATUS_PENDING,
                "remark" => $remark
            ]);
            $order->address()->create($address);

            foreach ($items as $item) {
                $variant = ProductVariant::find($item['variant_id']);
                $order->items()->create([
                    "variant_id" => $variant['id'],
                    "variant_code" => $variant['variant_code'],
                    "product_unit" => $variant->product['product_unit'],
                    "product_title" => $variant->product['product_title'],
                    "variant_title" => $variant['variant_title'],
                    "price" => $variant['price'],
                    "quantity" => $item['quantity'],
                ]);
                if ($variant->decreaseStock($item['quantity']) <= 0) {
                    throw (new HttpResponseException(response()->json([
                        'code' => 422,
                        "msg" => "该商品库存不足",
                        "data" => null,
                    ], 422)));
                }
                $order->items_amount += $variant['price'] * $item['quantity'];
            }
            $order->amount = $order->items_amount + $order->shipment_amount - $order->discounts_amount;
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

    static function items($items)
    {
        $variants = array();
        foreach ($items as $item) {
            $variant = ProductVariant::find($item['variant_id']);
            if (!$variant || !$variant->product->on_sale) {
                throw (new HttpResponseException(response()->json([
                    'code' => 422,
                    "msg" => "该商品不存在/未上架",
                    "data" => null,
                ], 422)));
            }
            $variants[] = [
                "variant_id" => $variant['id'],
                "variant_code" => $variant['variant_code'],
                "product_unit" => $variant->product['product_unit'],
                "product_title" => $variant->product['product_title'],
                "variant_title" => $variant['variant_title'],
                "price" => $variant['price'],
                "quantity" => $item['quantity'],
            ];
        }
        return $variants;
    }

}
