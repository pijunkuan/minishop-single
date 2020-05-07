<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function dashboard_static($type)
    {
        $day = [now()->startOfDay(), now()->endOfDay()];
        $yesterday = [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()];
        $week = [now()->startOfWeek(), now()->endOfWeek()];
        switch ($type) {
            case "order":
                $processing_count = Order::where('status', Order::ORDER_STATUS_PROCESSING)
                    ->where('refund_status', null)->count();
                $refunding_count = Order::where('refund_status', Order::REFUND_STATUS_REFUNDING)->count();
                $day_new_order_count = Order::whereBetween('created_at', $day)->count();
                $day_new_order_suc_amount = Order::whereBetween('created_at',$day)->sum('amount');
                $yesterday_order_count = Order::whereBetween('created_at', $yesterday)->count();
                $yesterday_order_amount = Order::whereBetween('created_at', $yesterday)->sum('amount');
                $yesterday_order_suc_amount = Order::whereIn('status', [
                    Order::ORDER_STATUS_PROCESSING,
                    Order::ORDER_STATUS_PARTIAL,
                    Order::ORDER_STATUS_SENT,
                    Order::ORDER_STATUS_SUCCESS
                ])->whereBetween('created_at', $yesterday)->sum('amount');
                $yesterday_order_rate = $yesterday_order_amount ? round($yesterday_order_suc_amount / $yesterday_order_amount, 2) * 100 : 0;
                $rs = [
                    "processing_count" => $processing_count,
                    "refunding_count" => $refunding_count,
                    "day_new_order_count" => $day_new_order_count,
                    "yesterday_order_count" => $yesterday_order_count,
                    "yesterday_order_amount" => $yesterday_order_amount,
                    "yesterday_order_suc_amount" => $yesterday_order_suc_amount,
                    "yesterday_order_rate" => $yesterday_order_rate
                ];
                break;
            case "customer":
                $today_customer_count = Customer::whereBetween('created_at', $day)->count();
                $yesterday_customer_count = Customer::whereBetween('created_at', $yesterday)->count();
                $week_customer_count = Customer::whereBetween('created_at', $week)->count();
                $rs = [
                    'today_customer_count' => $today_customer_count,
                    'yesterday_customer_count' => $yesterday_customer_count,
                    'week_customer_count' => $week_customer_count
                ];
                break;
            case "image":
                $bytes = round(Image::sum('img_bytes') / 1024, 2);
                if($bytes > 1024*1024){
                    $bytes_value = round($bytes / (1024*1024), 2) . " GB";
                }elseif ($bytes > 1024) {
                    $bytes_value = round($bytes / 1024, 2) . " MB";
                } else {
                    $bytes_value = $bytes ." kB";
                }
                $count = Image::count();
                $rs = [
                    "bytes" => $bytes,
                    "bytes_value" => $bytes_value,
                    "count" => $count
                ];
                break;
            default:
                return $this->jsonErrorResponse(404, '非法参数');
                break;
        }
        return $this->jsonSuccessResponse($rs);
    }
}
