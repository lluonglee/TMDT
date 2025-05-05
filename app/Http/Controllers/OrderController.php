<?php

namespace App\Http\Controllers;

use App\Mail\OrderSuccessMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Mail;


use Dompdf\Dompdf;
use Dompdf\Options;
//order
class OrderController extends Controller
{


    // public function order_place(Request $request)
    // {
    //     $payment_method = $request->input('payment_option');

    //     // Kiểm tra người dùng có đăng nhập không
    //     $customer_id = Session::get('customer_id');
    //     if (!$customer_id) {
    //         return redirect()->back()->with('error', 'Bạn cần đăng nhập để đặt hàng!');
    //     }

    //     // Kiểm tra địa chỉ giao hàng có tồn tại không
    //     $shipping_id = Session::get('shipping_id');
    //     if (!$shipping_id) {
    //         return redirect()->back()->with('error', 'Vui lòng thêm địa chỉ giao hàng trước khi đặt hàng!');
    //     }

    //     // Lấy giỏ hàng từ Session
    //     $cart = Session::get('cart', []);

    //     if (empty($cart)) {
    //         return redirect()->back()->with('error', 'Giỏ hàng trống, không thể đặt hàng!');
    //     }

    //     // Tính tổng tiền đơn hàng mà không tính giảm giá
    //     $order_total = 0;
    //     $discount_total = 0; // Tổng giá trị giảm giá (sẽ bỏ qua)

    //     foreach ($cart as $item) {
    //         // Sử dụng giá gốc thay vì giá sau giảm
    //         $total_price = $item['product_price'] * $item['quantity'];

    //         // Cộng vào tổng tiền đơn hàng
    //         $order_total += $total_price;
    //     }

    //     // Thêm thông tin thanh toán
    //     $payment_id = DB::table('tbl_payment')->insertGetId([
    //         'payment_method' => $payment_method,
    //         'payment_status' => 'Đang chờ xử lý',
    //         'created_at' => Carbon::now(),
    //     ]);

    //     // Thêm đơn hàng vào bảng tbl_order
    //     $order_id = DB::table('tbl_order')->insertGetId([
    //         'customer_id' => $customer_id,
    //         'shipping_id' => $shipping_id,
    //         'payment_id' => $payment_id,
    //         'order_total' => $order_total,
    //         'order_status' => 'Đang xử lý',
    //         'created_at' => Carbon::now(),
    //     ]);

    //     // Thêm dữ liệu vào bảng tbl_order_detail từ giỏ hàng session
    //     foreach ($cart as $item) {
    //         DB::table('tbl_order_detail')->insert([
    //             'order_id' => $order_id,
    //             'product_id' => $item['product_id'],
    //             'product_quantity' => $item['quantity'],
    //             'product_price' => $item['product_price'],
    //             'created_at' => Carbon::now(),
    //         ]);
    //     }

    //     // Gửi email thông báo đơn hàng thành công
    //     $customer_email = DB::table('tbl_customer')->where('customer_id', $customer_id)->value('customer_email');
    //     if ($customer_email) {
    //         $orderInfo = [  // Pass any order-related info to the email
    //             'order_id' => $order_id,
    //             'order_total' => $order_total,
    //         ];

    //         Mail::to($customer_email)->send(new OrderSuccessMail($orderInfo, $customer_email));
    //     }
    //     // Xóa giỏ hàng trong session sau khi đặt hàng
    //     Session::forget('cart');

    //     // Điều hướng sau khi đặt hàng
    //     if ($payment_method == 'bằng thẻ') {
    //         return redirect('/payment-card');
    //     } else {
    //         return redirect('/thank-you')->with('success', 'Đặt hàng thành công!');
    //     }
    // }

    // public function order_place(Request $request)
    // {
    //     $request->validate([
    //         'payment_option' => 'required|in:bằng thẻ,tiền mặt',
    //     ]);

    //     $customer_id = Session::get('customer_id');
    //     if (!$customer_id) {
    //         return Redirect::back()->with('error', 'Bạn cần đăng nhập để đặt hàng!');
    //     }

    //     $shipping_id = Session::get('shipping_id');
    //     if (!$shipping_id) {
    //         return Redirect::back()->with('error', 'Vui lòng thêm địa chỉ giao hàng trước khi đặt hàng!');
    //     }

    //     $cart = Session::get('cart', []);
    //     if (empty($cart)) {
    //         return Redirect::back()->with('error', 'Giỏ hàng trống, không thể đặt hàng!');
    //     }

    //     // Lấy shipping_fee từ tbl_shipping
    //     $shipping = DB::table('tbl_shipping')
    //         ->where('shipping_id', $shipping_id)
    //         ->select('shipping_fee')
    //         ->first();
    //     $shipping_fee = $shipping ? ($shipping->shipping_fee ?? 0) : 0;

    //     // Tính tổng tiền, giảm giá sản phẩm, và giảm giá mã
    //     $subtotal = 0;
    //     $product_discount_total = $request->product_discount_total ?? 0;
    //     $promotion_discount = $request->promotion_discount ?? 0;
    //     $promotion_code = $request->promotion_code ?? '';

    //     foreach ($cart as $item) {
    //         $discounted_price = $item['product_price'] * (1 - ($item['product_discount'] ?? 0) / 100);
    //         $subtotal += $item['product_price'] * $item['quantity'];
    //         $product_discount_total += ($item['product_price'] - $discounted_price) * $item['quantity'];
    //     }

    //     // Giới hạn promotion_discount tối đa bằng tổng tiền sau giảm giá sản phẩm
    //     $total_after_product_discount = $subtotal - $product_discount_total;
    //     $promotion_discount = min($promotion_discount, $total_after_product_discount);

    //     // Tính tổng tiền đơn hàng, bao gồm shipping_fee
    //     $order_total = max(0, $total_after_product_discount - $promotion_discount + $shipping_fee);

    //     // Thêm thông tin thanh toán
    //     $payment_id = DB::table('tbl_payment')->insertGetId([
    //         'payment_method' => $request->payment_option,
    //         'payment_status' => 'Đang chờ xử lý',
    //         'created_at' => Carbon::now(),
    //     ]);

    //     // Thêm đơn hàng vào bảng tbl_order
    //     $order_id = DB::table('tbl_order')->insertGetId([
    //         'customer_id' => $customer_id,
    //         'shipping_id' => $shipping_id,
    //         'payment_id' => $payment_id,
    //         'order_total' => $order_total,
    //         'shipping_fee' => $shipping_fee, // Lưu shipping_fee
    //         'discount_code' => $promotion_code,
    //         'discount_amount' => $promotion_discount,
    //         'order_status' => 'Đang xử lý',
    //         'created_at' => Carbon::now(),
    //         'updated_at' => Carbon::now(),
    //     ]);

    //     // Thêm chi tiết đơn hàng
    //     foreach ($cart as $item) {
    //         $discounted_price = $item['product_price'] * (1 - ($item['product_discount'] ?? 0) / 100);
    //         $original_price = $item['product_price'];
    //         DB::table('tbl_order_detail')->insert([
    //             'order_id' => $order_id,
    //             'product_id' => $item['product_id'],
    //             'product_quantity' => $item['quantity'],
    //             'product_price' => $discounted_price, // Giá sau giảm sản phẩm
    //             'original_price' => $original_price, // Giá gốc
    //             'created_at' => Carbon::now(),
    //             'updated_at' => Carbon::now(),
    //         ]);
    //     }

    //     // Cập nhật số lần sử dụng mã khuyến mãi
    //     if ($promotion_code && $promotion_discount > 0) {
    //         DB::table('tbl_promotion')
    //             ->where('code', $promotion_code)
    //             ->increment('used_count');
    //     }

    //     // Gửi email thông báo đơn hàng thành công (nếu bật)
    //     $customer_email = DB::table('tbl_customer')->where('customer_id', $customer_id)->value('customer_email');
    //     if ($customer_email) {
    //         $orderInfo = [
    //             'order_id' => $order_id,
    //             'order_total' => $order_total,
    //             'shipping_fee' => $shipping_fee, // Thêm shipping_fee
    //         ];
    //         Mail::to($customer_email)->send(new OrderSuccessMail($orderInfo, $customer_email));
    //     }

    //     // Xóa giỏ hàng, mã khuyến mãi và shipping
    //     Session::forget(['cart', 'promotion_discount', 'promotion_code', 'shipping_id', 'shipping_fee']);

    //     // Điều hướng
    //     if ($request->payment_option == 'bằng thẻ') {
    //         return Redirect::to('/payment-card');
    //     } else {
    //         return Redirect::to('/thank-you')->with('success', 'Đặt hàng thành công!');
    //     }
    // }

    public function listOrders(Request $request)
    {
        $status = $request->input('status'); // lấy giá trị filter từ query string

        $query = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->select(
                'tbl_order.*',
                'tbl_customer.customer_name',
                'tbl_shipping.shipping_fee',
                'tbl_payment.payment_method'
            );

        if ($status && in_array($status, ['Đang xử lý', 'Hoàn thành', 'Đã hủy'])) {
            $query->where('order_status', $status);
        }

        $all_orders = $query->orderBy('tbl_order.created_at', 'desc')->get();

        return view('admin.manage_order', compact('all_orders', 'status'));
    }


    public function thank_you()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();
        $session_id = session()->getId();
        $messages = DB::table('tbl_chat_messages')
            ->where('session_id', $session_id)
            ->get();


        return view('pages.thankyou.thank_you')->with([
            'categories' => $categories,
            'brands' => $brands,
            'messages' => $messages
        ]);
    }

    public function orderHistory()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();


        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem lịch sử đơn hàng.');
        }

        // Lấy danh sách đơn hàng của khách hàng, bao gồm shipping_fee
        $orders = DB::table('tbl_order')
            ->where('customer_id', $customer_id)
            ->select('order_id', 'order_total', 'order_status', 'discount_code', 'discount_amount', 'created_at', 'shipping_fee')
            ->orderBy('created_at', 'desc')
            ->get();
        $session_id = session()->getId();
        $messages = DB::table('tbl_chat_messages')
            ->where('session_id', $session_id)
            ->get();

        return view('pages.order.history', compact('orders'))->with([
            'categories' => $categories,
            'brands' => $brands,
            'messages' => $messages
        ]);
    }

    public function orderDetail($order_id)
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();

        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return Redirect::to('/login')->with('error', 'Vui lòng đăng nhập để xem chi tiết đơn hàng');
        }

        $order = DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->where('customer_id', $customer_id)
            ->select('order_id', 'order_total', 'order_status', 'discount_code', 'discount_amount', 'created_at', 'shipping_id', 'payment_id', 'shipping_fee')
            ->first();

        if (!$order) {
            return Redirect::to('/order-history')->with('error', 'Đơn hàng không tồn tại.');
        }

        $order_details = DB::table('tbl_order_detail')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->where('tbl_order_detail.order_id', $order_id)
            ->select(
                'tbl_order_detail.*',
                'tbl_product.product_name',
                'tbl_product.product_image',
                'tbl_product.discount as product_discount'
            )
            ->get();

        $shipping = DB::table('tbl_shipping')
            ->where('shipping_id', $order->shipping_id)
            ->select('shipping_name', 'shipping_address', 'shipping_phone', 'shipping_email')
            ->first();

        $payment = DB::table('tbl_payment')
            ->where('payment_id', $order->payment_id)
            ->select('payment_method', 'payment_status')
            ->first();
        $session_id = session()->getId();
        $messages = DB::table('tbl_chat_messages')
            ->where('session_id', $session_id)
            ->get();

        $subtotal = 0;
        $product_discount_total = 0;
        foreach ($order_details as $item) {
            $original_price = $item->original_price ?? $item->product_price / (1 - ($item->product_discount / 100));
            $subtotal += $original_price * $item->product_quantity;
            $product_discount_total += ($original_price - $item->product_price) * $item->product_quantity;
        }

        // Giới hạn promotion_discount tối đa bằng tổng tiền sau giảm giá sản phẩm
        $total_after_product_discount = $subtotal - $product_discount_total;
        $order->discount_amount = min($order->discount_amount ?? 0, $total_after_product_discount);

        return view('pages.order.detail', compact('order', 'order_details', 'categories', 'brands', 'shipping', 'payment', 'subtotal', 'product_discount_total', 'messages'));
    }

    public function manage_order()
    {
        $all_orders = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->select(
                'tbl_order.*',
                'tbl_customer.customer_name',
                'tbl_payment.payment_method',
                'tbl_order.shipping_fee' // Thêm shipping_fee
            )
            ->orderBy('tbl_order.order_id', 'desc')
            ->get();

        return view('admin.manage_order')->with('all_orders', $all_orders);
    }

    public function view_order($orderId)
    {
        $order_details = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->join('tbl_order_detail', 'tbl_order.order_id', '=', 'tbl_order_detail.order_id')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->where('tbl_order.order_id', $orderId)
            ->select(
                'tbl_order.order_id',
                'tbl_order.order_total',
                'tbl_order.order_status',
                'tbl_order.discount_code',
                'tbl_order.discount_amount',
                'tbl_order.created_at',
                'tbl_order.shipping_fee', // Thêm shipping_fee
                'tbl_customer.customer_name',
                'tbl_customer.customer_email',
                'tbl_customer.customer_phone',
                'tbl_shipping.shipping_name',
                'tbl_shipping.shipping_address',
                'tbl_shipping.shipping_phone',
                'tbl_shipping.shipping_email',
                'tbl_payment.payment_method',
                'tbl_payment.payment_status',
                'tbl_order_detail.order_detail_id',
                'tbl_order_detail.product_id',
                'tbl_order_detail.product_quantity',
                'tbl_order_detail.product_price',
                'tbl_order_detail.original_price',
                'tbl_product.product_name',
                'tbl_product.discount as product_discount'
            )
            ->get();

        $order = $order_details->first();
        if (!$order) {
            return Redirect::to('/manage-order')->with('error', 'Đơn hàng không tồn tại.');
        }

        $subtotal = 0;
        $product_discount_total = 0;
        foreach ($order_details as $detail) {
            $original_price = $detail->original_price ?? ($detail->product_price / (1 - ($detail->product_discount / 100)));
            $subtotal += $original_price * $detail->product_quantity;
            $product_discount_total += ($original_price - $detail->product_price) * $detail->product_quantity;
        }

        // Giới hạn discount_amount tối đa bằng tổng tiền sau giảm giá sản phẩm
        $total_after_product_discount = $subtotal - $product_discount_total;
        $order->discount_amount = min($order->discount_amount ?? 0, $total_after_product_discount);

        return view('admin.view_order')->with([
            'order_details' => $order_details,
            'order' => $order,
            'subtotal' => $subtotal,
            'product_discount_total' => $product_discount_total,
        ]);
    }
    public function updateOrderStatus(Request $request, $orderId)
    {
        // Validate trạng thái đơn hàng
        $request->validate([
            'order_status' => 'required|in:Đang xử lý,Hoàn thành,Đã hủy',
        ]);

        // Cập nhật trạng thái đơn hàng
        DB::table('tbl_order')
            ->where('order_id', $orderId)
            ->update([
                'order_status' => $request->order_status,
            ]);

        // Thông báo cập nhật thành công
        return redirect()->back()->with('message', 'Cập nhật trạng thái đơn hàng thành công');
    }
    public function deleteOrder($orderId)
    {
        // Kiểm tra đơn hàng tồn tại
        $order = DB::table('tbl_order')->where('order_id', $orderId)->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Đơn hàng không tồn tại!');
        }

        // Xóa đơn hàng
        DB::table('tbl_order')->where('order_id', $orderId)->delete();

        return redirect()->back()->with('message', 'Đơn hàng đã được xóa thành công!');
    }

    // in hóa đơn
    public function print_invoice($orderId)
    {
        // Lấy dữ liệu đơn hàng
        $order = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->select(
                'tbl_order.order_id',
                'tbl_order.order_total',
                'tbl_order.order_status',
                'tbl_order.discount_code',
                'tbl_order.discount_amount',
                'tbl_order.created_at',
                'tbl_customer.customer_name',
                'tbl_customer.customer_email',
                'tbl_customer.customer_phone',
                'tbl_shipping.shipping_name',
                'tbl_shipping.shipping_address',
                'tbl_shipping.shipping_phone',
                'tbl_shipping.shipping_email',
                'tbl_payment.payment_method',
                'tbl_payment.payment_status'
            )
            ->where('tbl_order.order_id', $orderId)
            ->first();

        if (!$order) {
            return Redirect::to('/manage-order')->with('error', 'Đơn hàng không tồn tại.');
        }

        // Lấy chi tiết đơn hàng
        $order_details = DB::table('tbl_order_detail')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->where('tbl_order_detail.order_id', $orderId)
            ->select(
                'tbl_order_detail.order_detail_id',
                'tbl_order_detail.product_id',
                'tbl_order_detail.product_quantity',
                'tbl_order_detail.product_price',
                'tbl_order_detail.original_price',
                'tbl_product.product_name',
                'tbl_product.discount as product_discount'
            )
            ->get();

        // Tính tổng tiền gốc và giảm giá sản phẩm
        $subtotal = 0;
        $product_discount_total = 0;
        foreach ($order_details as $detail) {
            $original_price = $detail->original_price ?? ($detail->product_price / (1 - ($detail->product_discount / 100)));
            $subtotal += $original_price * $detail->product_quantity;
            $product_discount_total += ($original_price - $detail->product_price) * $detail->product_quantity;
        }

        // Giới hạn discount_amount tối đa bằng tổng tiền sau giảm giá sản phẩm
        $total_after_product_discount = $subtotal - $product_discount_total;
        $promotion_discount = min($order->discount_amount ?? 0, $total_after_product_discount);

        // Khởi tạo Dompdf với tùy chọn hỗ trợ UTF-8
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);

        // Tạo nội dung HTML cho hóa đơn
        $html = view('admin.print_invoice', compact('order', 'order_details', 'subtotal', 'product_discount_total', 'promotion_discount'))->render();

        // Load HTML vào Dompdf
        $dompdf->loadHtml($html);

        // Đặt kích thước và chế độ giấy
        $dompdf->setPaper('A4', 'portrait');

        // Tạo file PDF
        $dompdf->render();

        // Xuất file PDF ra trình duyệt
        return $dompdf->stream('invoice_' . $order->order_id . '.pdf');
    }
}
