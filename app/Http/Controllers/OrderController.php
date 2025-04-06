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
    //
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

    //     // Tính tổng tiền đơn hàng và giảm giá
    //     $order_total = 0;
    //     $discount_total = 0; // Tổng giá trị giảm giá

    //     foreach ($cart as $item) {
    //         // Tính giá sau giảm
    //         $discount_price = $item['product_price'] * (1 - ($item['discount'] ?? 0) / 100);
    //         $total_price = $discount_price * $item['quantity'];

    //         // Cộng tổng giá trị giảm giá
    //         $discount_value = ($item['product_price'] - $discount_price) * $item['quantity'];
    //         $discount_total += $discount_value;

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

    //     // Xóa giỏ hàng trong session sau khi đặt hàng
    //     Session::forget('cart');

    //     // Điều hướng sau khi đặt hàng
    //     if ($payment_method == 'bằng thẻ') {
    //         return redirect('/payment-card');
    //     } else {
    //         return redirect('/thank-you')->with('success', 'Đặt hàng thành công!');
    //     }
    // }

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

        // Tính tổng tiền đơn hàng mà không tính giảm giá
        $order_total = 0;
        $discount_total = 0; // Tổng giá trị giảm giá (sẽ bỏ qua)

        foreach ($cart as $item) {
            // Sử dụng giá gốc thay vì giá sau giảm
            $total_price = $item['product_price'] * $item['quantity'];

            // Cộng vào tổng tiền đơn hàng
            $order_total += $total_price;
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
        //phần thêm vào
        $customer_email = DB::table('tbl_customer')->where('customer_id', $customer_id)->value('customer_email');
        if ($customer_email) {
            $orderInfo = [  // Pass any order-related info to the email
                'order_id' => $order_id,
                'order_total' => $order_total,
            ];

            Mail::to($customer_email)->send(new OrderSuccessMail($orderInfo, $customer_email));
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


    //order mail

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

    //     foreach ($cart as $item) {
    //         $total_price = $item['product_price'] * $item['quantity'];
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

    //     // Lấy email khách hàng
    //     $customer_email = DB::table('tbl_customer')->where('customer_id', $customer_id)->value('customer_email');

    //     // Gửi email thông báo đơn hàng thành công
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
    //     return redirect('/thank-you')->with('success', 'Đặt hàng thành công!');
    // }



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
    // public function orderDetail($order_id)
    // {
    //     $categories = DB::table('tbl_category_product')
    //         ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
    //         ->orderBy('category_id', 'desc')
    //         ->get();

    //     $brands = DB::table('tbl_brand')
    //         ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
    //         ->orderBy('brand_id', 'desc')
    //         ->get();

    //     $customer_id = Session::get('customer_id');

    //     // Lấy thông tin đơn hàng
    //     $order = DB::table('tbl_order')
    //         ->where('order_id', $order_id)
    //         ->where('customer_id', $customer_id)
    //         ->first();

    //     // Nếu không tìm thấy đơn hàng, redirect đến lịch sử đơn hàng
    //     if (!$order) {
    //         return redirect('/order-history')->with('error', 'Đơn hàng không tồn tại.');
    //     }

    //     // Lấy chi tiết sản phẩm trong đơn hàng
    //     $order_details = DB::table('tbl_order_detail')
    //         ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
    //         ->where('order_id', $order_id)
    //         ->select('tbl_order_detail.*', 'tbl_product.product_name', 'tbl_product.product_image')
    //         ->get();

    //     // Kiểm tra trạng thái đơn hàng, cập nhật nếu cần
    //     if ($order->order_status != $order->status) {
    //         DB::table('tbl_order')
    //             ->where('order_id', $order_id)
    //             ->update(['order_status' => $order->status]); // Cập nhật trạng thái
    //     }

    //     // Trả về view chi tiết đơn hàng
    //     return view('pages.order.detail', compact('order', 'order_details'))->with([
    //         'categories' => $categories, // Truyền đúng biến
    //         'brands' => $brands,
    //     ]);
    // }
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
        // Kiểm tra xem người dùng đã đăng nhập chưa
        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập để xem chi tiết đơn hàng');
        }

        // Lấy thông tin đơn hàng cho khách hàng
        $order = DB::table('tbl_order')
            ->where('order_id', $order_id)
            ->where('customer_id', $customer_id)
            ->first();

        // Nếu không tìm thấy đơn hàng
        if (!$order) {
            return redirect('/order-history')->with('error', 'Đơn hàng không tồn tại.');
        }

        // Lấy chi tiết sản phẩm trong đơn hàng
        $order_details = DB::table('tbl_order_detail')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->where('order_id', $order_id)
            ->select('tbl_order_detail.*', 'tbl_product.product_name', 'tbl_product.product_image')
            ->get();

        // Trả về view chi tiết đơn hàng
        return view('pages.order.detail', compact('order', 'order_details'))->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,
        ]);
    }


    public function manage_order()
    {
        $all_orders = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id') // Thêm dòng này
            ->select(
                'tbl_order.*',
                'tbl_customer.customer_name',
                'tbl_payment.payment_method' // Lấy phương thức thanh toán
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
                'tbl_order.*',
                'tbl_customer.*',
                'tbl_shipping.*',
                'tbl_payment.*',
                'tbl_order_detail.*',
                'tbl_product.product_name'
            )
            ->get();

        $order = $order_details->first(); // để hiển thị chung
        return view('admin.view_order')->with([
            'order_details' => $order_details,
            'order' => $order
        ]);
    }
    // public function updateOrderStatus(Request $request, $orderId)
    // {
    //     // Validate trạng thái đơn hàng
    //     $request->validate([
    //         'order_status' => 'required|in:Đang xử lý,Hoàn thành,Đã hủy',
    //     ]);

    //     // Cập nhật trạng thái đơn hàng
    //     DB::table('tbl_order')
    //         ->where('order_id', $orderId)
    //         ->update([
    //             'order_status' => $request->order_status,
    //         ]);

    //     // Thông báo cập nhật thành công
    //     return redirect()->back()->with('message', 'Cập nhật trạng thái đơn hàng thành công');
    // }
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

    // in hóa đơn


    public function print_invoice($orderId)
    {
        // Lấy dữ liệu đơn hàng và chi tiết sản phẩm
        $order = DB::table('tbl_order')
            ->join('tbl_customer', 'tbl_order.customer_id', '=', 'tbl_customer.customer_id')
            ->join('tbl_shipping', 'tbl_order.shipping_id', '=', 'tbl_shipping.shipping_id')
            ->join('tbl_payment', 'tbl_order.payment_id', '=', 'tbl_payment.payment_id')
            ->select(
                'tbl_order.*',
                'tbl_customer.customer_name',
                'tbl_customer.customer_email',
                'tbl_customer.customer_phone',
                'tbl_shipping.shipping_name',
                'tbl_shipping.shipping_address',
                'tbl_shipping.shipping_phone',
                'tbl_payment.payment_method',
                'tbl_payment.payment_status'
            )
            ->where('tbl_order.order_id', $orderId)
            ->first();

        $order_details = DB::table('tbl_order_detail')
            ->join('tbl_product', 'tbl_order_detail.product_id', '=', 'tbl_product.product_id')
            ->where('tbl_order_detail.order_id', $orderId)
            ->select('tbl_order_detail.*', 'tbl_product.product_name')
            ->get();

        // Khởi tạo Dompdf
        $dompdf = new Dompdf();

        // Tạo nội dung HTML cho hóa đơn
        $html = view('admin.print_invoice', compact('order', 'order_details'))->render();

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
