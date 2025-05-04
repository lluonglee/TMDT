<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StatistiController extends Controller
{
    public function index(Request $request)
    {
        // Lấy filter_type, mặc định là 'range'
        $filter_type = $request->query('filter_type', 'range');
        $start_date = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $day = $request->query('day', Carbon::now()->format('Y-m-d'));
        $week = $request->query('week', Carbon::now()->format('Y-W'));
        $year = $request->query('year', Carbon::now()->format('Y'));

        // Xây dựng query cơ bản cho đơn hàng
        $order_query = DB::table('tbl_order');
        $customer_query = DB::table('tbl_customer');
        $error_message = null;

        // Xử lý theo filter_type
        try {
            if ($filter_type === 'range') {
                $start = Carbon::parse($start_date)->startOfDay();
                $end = Carbon::parse($end_date)->endOfDay();
                if ($start > $end) {
                    $error_message = 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.';
                    $start = Carbon::now()->startOfMonth();
                    $end = Carbon::now()->endOfMonth();
                    $start_date = $start->format('Y-m-d');
                    $end_date = $end->format('Y-m-d');
                }
                $order_query->whereBetween('tbl_order.created_at', [$start, $end]);
                $customer_query->whereBetween('tbl_customer.created_at', [$start, $end]);
            } elseif ($filter_type === 'day') {
                $date = Carbon::parse($day)->startOfDay();
                $order_query->whereDate('tbl_order.created_at', $date);
                $customer_query->whereDate('tbl_customer.created_at', $date);
            } elseif ($filter_type === 'week') {
                $parts = explode('-', $week);
                if (count($parts) !== 2 || !is_numeric($parts[0]) || !is_numeric($parts[1])) {
                    throw new \Exception('Định dạng tuần không hợp lệ.');
                }
                $order_query->whereYear('tbl_order.created_at', $parts[0])
                    ->whereWeek('tbl_order.created_at', $parts[1]);
                $customer_query->whereYear('tbl_customer.created_at', $parts[0])
                    ->whereWeek('tbl_customer.created_at', $parts[1]);
            } elseif ($filter_type === 'year') {
                if (!is_numeric($year) || strlen($year) !== 4) {
                    throw new \Exception('Định dạng năm không hợp lệ.');
                }
                $order_query->whereYear('tbl_order.created_at', $year);
                $customer_query->whereYear('tbl_customer.created_at', $year);
            } else {
                $error_message = 'Loại bộ lọc không hợp lệ.';
                $filter_type = 'range';
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $start_date = $start->format('Y-m-d');
                $end_date = $end->format('Y-m-d');
                $order_query->whereBetween('tbl_order.created_at', [$start, $end]);
                $customer_query->whereBetween('tbl_customer.created_at', [$start, $end]);
            }
        } catch (\Exception $e) {
            $error_message = $e->getMessage() ?: 'Dữ liệu nhập không hợp lệ.';
            $filter_type = 'range';
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $start_date = $start->format('Y-m-d');
            $end_date = $end->format('Y-m-d');
            $order_query->whereBetween('tbl_order.created_at', [$start, $end]);
            $customer_query->whereBetween('tbl_customer.created_at', [$start, $end]);
        }

        if ($error_message) {
            Session::flash('message', $error_message);
        }

        // Tổng doanh thu, phí ship, số đơn hàng
        $total_stats = (clone $order_query)->selectRaw('
            SUM(order_total) as total_revenue,
            SUM(shipping_fee) as total_shipping_fee,
            COUNT(*) as total_orders
        ')->first();

        // Khởi tạo mặc định để tránh lỗi undefined
        $total_revenue = $total_stats->total_revenue ?? 0;
        $total_shipping_fee = $total_stats->total_shipping_fee ?? 0;
        $total_orders = $total_stats->total_orders ?? 0;

        // Thống kê theo trạng thái
        $status_stats = (clone $order_query)->selectRaw('
            order_status,
            COUNT(*) as order_count,
            SUM(order_total) as total_revenue,
            SUM(shipping_fee) as total_shipping_fee
        ')->groupBy('order_status')->get();

        // Top sản phẩm bán chạy
        $top_products = (clone $order_query)
            ->join('tbl_order_detail', 'tbl_order.order_id', '=', 'tbl_order_detail.order_id')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->selectRaw('
                tbl_product.product_name,
                SUM(tbl_order_detail.product_quantity) as total_quantity,
                SUM(tbl_order_detail.product_price * tbl_order_detail.product_quantity) as total_revenue
            ')
            ->groupBy('tbl_product.product_name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        // Biểu đồ doanh thu theo tháng
        $chart_data = (clone $order_query)
            ->selectRaw("DATE_FORMAT(tbl_order.created_at, '%Y-%m') as month, SUM(order_total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chart_labels = $chart_data->pluck('month')->toArray();
        $chart_values = $chart_data->pluck('revenue')->toArray();

        // Thống kê tài khoản khách hàng
        $customer_stats = [
            'total_customers' => (clone $customer_query)->count(),
            'new_customers' => (clone $customer_query)->count(),
            'active_customers' => (clone $order_query)
                ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
                ->distinct('tbl_customer.customer_id')
                ->count('tbl_customer.customer_id'),
        ];

        // Truyền dữ liệu vào view
        return view('admin.statistics', [
            'total_revenue' => $total_revenue,
            'total_shipping_fee' => $total_shipping_fee,
            'total_orders' => $total_orders,
            'status_stats' => $status_stats,
            'top_products' => $top_products,
            'filter_type' => $filter_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'day' => $day,
            'week' => $week,
            'year' => $year,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_values,
            'customer_stats' => $customer_stats,
        ]);
    }
}
