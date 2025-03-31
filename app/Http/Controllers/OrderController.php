<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
//order
class OrderController extends Controller
{
    //
    public function order_place(Request $request)
    {
        $payment_method = $request->input('payment_option');

        // Kiểm tra người dùng có đăng nhập không
        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để đặt hàng!');
        }

        // Kiểm tra địa chỉ giao hàng có tồn tại không
        $shipping_id = Session::get('shipping_id');
        if (!$shipping_id) {
            return redirect()->back()->with('error', 'Vui lòng thêm địa chỉ giao hàng trước khi đặt hàng!');
        }

        // Lấy giỏ hàng từ Session
        $cart = Session::get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Giỏ hàng trống, không thể đặt hàng!');
        }

        // Tính tổng tiền đơn hàng
        $order_total = 0;
        foreach ($cart as $item) {
            $order_total += $item['product_price'] * $item['quantity'];
        }

        // Thêm thông tin thanh toán
        $payment_id = DB::table('tbl_payment')->insertGetId([
            'payment_method' => $payment_method,
            'payment_status' => 'Đang chờ xử lý',
            'created_at' => Carbon::now(),
        ]);

        // Thêm đơn hàng vào bảng tbl_order
        $order_id = DB::table('tbl_order')->insertGetId([
            'customer_id' => $customer_id,
            'shipping_id' => $shipping_id,
            'payment_id' => $payment_id,
            'order_total' => $order_total,
            'order_status' => 'Đang xử lý',
            'created_at' => Carbon::now(),
        ]);

        // Thêm dữ liệu vào bảng tbl_order_detail từ giỏ hàng session
        foreach ($cart as $item) {
            DB::table('tbl_order_detail')->insert([
                'order_id' => $order_id,
                'product_id' => $item['product_id'],
                'product_quantity' => $item['quantity'],
                'product_price' => $item['product_price'],
                'created_at' => Carbon::now(),
            ]);
        }

        // Xóa giỏ hàng trong session sau khi đặt hàng
        Session::forget('cart');
        // Điều hướng sau khi đặt hàng
        if ($payment_method == 'bằng thẻ') {
            return redirect('/payment-card');
        } else {
            return redirect('/thank-you')->with('success', 'Đặt hàng thành công!');
        }
    }
    public function thank_you()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();

        return view('pages.thankyou.thank_you')->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,

        ]);
    }
    public function orderHistory()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();
        $customer_id = Session::get('customer_id');

        if (!$customer_id) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem lịch sử đơn hàng.');
        }

        // Lấy danh sách đơn hàng của khách hàng
        $orders = DB::table('tbl_order')
            ->where('customer_id', $customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.order.history', compact('orders'))->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,

        ]);;
    }
    public function orderDetail($order_id)
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();
        $customer_id = Session::get('customer_id');

        $order = DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->where('customer_id', $customer_id)
            ->first();

        if (!$order) {
            return redirect('/order-history')->with('error', 'Đơn hàng không tồn tại.');
        }

        $order_details = DB::table('tbl_order_detail')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->where('order_id', $order_id)
            ->select('tbl_order_detail.*', 'tbl_product.product_name', 'tbl_product.product_image')
            ->get();

        return view('pages.order.detail', compact('order', 'order_details'))->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,

        ]);;;
    }
}
