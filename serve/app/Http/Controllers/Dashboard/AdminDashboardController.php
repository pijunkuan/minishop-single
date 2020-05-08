<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Image;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $day_new_order_suc_amount = Order::whereIn('status', [
                    Order::ORDER_STATUS_PROCESSING,
                    Order::ORDER_STATUS_PARTIAL,
                    Order::ORDER_STATUS_SENT,
                    Order::ORDER_STATUS_SUCCESS
                ])->whereBetween('created_at', $day)->sum('amount');
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
                    "day_new_order_suc_amount" => $day_new_order_suc_amount,
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
                $bytes = round(Image::sum('img_bytes') / pow(1024, 1), 2);
                if ($bytes > pow(1024, 2)) {
                    $bytes_value = round($bytes / pow(1024, 2), 2) . " GB";
                } elseif ($bytes > 1024) {
                    $bytes_value = round($bytes / pow(1024, 1), 2) . " MB";
                } else {
                    $bytes_value = $bytes . " kB";
                }
                $count = Image::count();
                $rs = [
                    "bytes" => $bytes,
                    "bytes_value" => $bytes_value,
                    "bytes_percent" => round($bytes / (1 * pow(1024, 2)) * 100, 3),
                    "count" => $count
                ];
                break;
            case "order_line":
                $yesterday = Order::whereBetween('created_at', $yesterday)->whereIn('status', [
                    Order::ORDER_STATUS_PROCESSING,
                    Order::ORDER_STATUS_PARTIAL,
                    Order::ORDER_STATUS_SENT,
                    Order::ORDER_STATUS_SUCCESS
                ])->groupBy('name')->get([
                    DB::raw("DATE_FORMAT(created_at, '%H' ) as name"),
                    DB::raw("SUM(amount) as value")
                ]);
                $today = Order::whereBetween('created_at', $day)->whereIn('status', [
                    Order::ORDER_STATUS_PROCESSING,
                    Order::ORDER_STATUS_PARTIAL,
                    Order::ORDER_STATUS_SENT,
                    Order::ORDER_STATUS_SUCCESS
                ])->groupBy('name')->get([
                    DB::raw("DATE_FORMAT(created_at, '%H' ) as name"),
                    DB::raw("SUM(amount) as value")
                ]);
                $hour = date("G", time());
                $today = $this->hourArrayMake($hour, $today);
                $yesterday = $this->hourArrayMake(24, $yesterday);
                $rs = [
                    'today' => $this->arraySum($today),
                    'yesterday' => $this->arraySum($yesterday),
                ];
                break;
            default:
                return $this->jsonErrorResponse(404, '非法参数');
                break;
        }
        return $this->jsonSuccessResponse($rs);
    }

    private function hourArrayMake($hour, $datas)
    {
        $news = array();
        for ($i = 0; $i <= $hour; $i++) {
            $news[] = [
                "name" => $i,
                "value" => 0
            ];
        }
        foreach ($datas as $data) {
            foreach ($news as $key => $new) {
                if ($new['name'] == $data['name'] * 1.00)
                    $news[$key]['value'] += $data['value'];
            }
        }
        return $news;
    }

    private function arraySum($datas)
    {
        $last = count($datas);
        $temp = array();
        for ($i = 0; $i < $last; $i++) {
            if($i!= 0){
                $datas[$i]["value"] += $datas[$i-1]['value'];
            }
            $temp[] = $datas[$i]['value'];
        }
        return $temp;
    }
}
