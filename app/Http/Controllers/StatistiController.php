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
        // Lấy start_date và end_date, mặc định là tháng hiện tại
        $start_date = $request->query('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->query('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Validate định dạng ngày
        try {
            $start = Carbon::parse($start_date)->startOfDay();
            $end = Carbon::parse($end_date)->endOfDay();

            // Kiểm tra start_date <= end_date
            if ($start > $end) {
                Session::flash('message', 'Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc.');
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $start_date = $start->format('Y-m-d');
                $end_date = $end->format('Y-m-d');
            }
        } catch (\Exception $e) {
            Session::flash('message', 'Định dạng ngày không hợp lệ.');
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $start_date = $start->format('Y-m-d');
            $end_date = $end->format('Y-m-d');
        }

        // Xây dựng query cơ bản
        $query = DB::table('tbl_order')
            ->whereBetween('tbl_order.created_at', [$start, $end]);

        // Tổng doanh thu, phí ship, số đơn hàng
        $total_stats = (clone $query)->selectRaw('
            SUM(order_total) as total_revenue,
            SUM(shipping_fee) as total_shipping_fee,
            COUNT(*) as total_orders
        ')->first();

        // Khởi tạo mặc định để tránh lỗi undefined
        $total_revenue = $total_stats->total_revenue ?? 0;
        $total_shipping_fee = $total_stats->total_shipping_fee ?? 0;
        $total_orders = $total_stats->total_orders ?? 0;

        // Thống kê theo trạng thái
        $status_stats = (clone $query)->selectRaw('
            order_status,
            COUNT(*) as order_count,
            SUM(order_total) as total_revenue,
            SUM(shipping_fee) as total_shipping_fee
        ')->groupBy('order_status')->get();

        // Top sản phẩm bán chạy
        $top_products = (clone $query)
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
        $chart_data = (clone $query)
            ->selectRaw("DATE_FORMAT(tbl_order.created_at, '%Y-%m') as month, SUM(order_total) as revenue")
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $chart_labels = $chart_data->pluck('month')->toArray();
        $chart_values = $chart_data->pluck('revenue')->toArray();

        // Truyền dữ liệu vào view
        return view('admin.statistics', [
            'total_revenue' => $total_revenue,
            'total_shipping_fee' => $total_shipping_fee,
            'total_orders' => $total_orders,
            'status_stats' => $status_stats,
            'top_products' => $top_products,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'chart_labels' => $chart_labels,
            'chart_data' => $chart_values,
        ]);
    }
}
