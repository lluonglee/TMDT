<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use App\Mail\OrderSuccessMail;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function order_place(Request $request)
    {
        Log::info('Order place started', $request->all());

        $request->validate([
            'payment_option' => 'required|in:bằng thẻ,tiền mặt,VNPay',
            'total_vnpay' => 'required|numeric|min:0',
            'language' => 'required|in:vn,en',
            'bankCode' => 'nullable|string',
        ]);

        $customer_id = Session::get('customer_id');
        if (!$customer_id) {
            Log::warning('Missing customer_id');
            return Redirect::back()->with('error', 'Bạn cần đăng nhập để đặt hàng!');
        }

        $shipping_id = Session::get('shipping_id');
        if (!$shipping_id) {
            Log::warning('Missing shipping_id');
            return Redirect::back()->with('error', 'Vui lòng thêm địa chỉ giao hàng trước khi đặt hàng!');
        }

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            Log::warning('Empty cart');
            return Redirect::back()->with('error', 'Giỏ hàng trống, không thể đặt hàng!');
        }

        $shipping = DB::table('tbl_shipping')
            ->where('shipping_id', $shipping_id)
            ->select('shipping_fee')
            ->first();
        $shipping_fee = $shipping ? ($shipping->shipping_fee ?? 0) : 0;

        $subtotal = $request->input('subtotal', 0);
        $product_discount_total = $request->input('product_discount_total', 0);
        $promotion_discount = $request->input('promotion_discount', 0);
        $promotion_code = $request->input('promotion_code', '');
        $total_vnpay = $request->input('total_vnpay', 0);

        $total_after_product_discount = $subtotal - $product_discount_total;
        $promotion_discount = min($promotion_discount, $total_after_product_discount);
        $order_total = max(0, $total_after_product_discount - $promotion_discount + $shipping_fee);

        if ($request->payment_option === 'VNPay' && abs($total_vnpay - $order_total) > 0.01) {
            Log::warning('Total mismatch', ['total_vnpay' => $total_vnpay, 'order_total' => $order_total]);
            return Redirect::back()->with('error', 'Tổng tiền không hợp lệ!');
        }

        try {
            DB::beginTransaction();

            $payment_id = DB::table('tbl_payment')->insertGetId([
                'payment_method' => $request->payment_option,
                'payment_status' => $request->payment_option === 'VNPay' ? 'pending' : 'Đang chờ xử lý',
                'created_at' => Carbon::now(),
            ]);

            $session_id = session()->getId();
            $order_id = DB::table('tbl_order')->insertGetId([
                'customer_id' => $customer_id,
                'shipping_id' => $shipping_id,
                'payment_id' => $payment_id,
                'order_total' => $order_total,
                'shipping_fee' => $shipping_fee,
                'discount_code' => $promotion_code,
                'discount_amount' => $promotion_discount,
                'order_status' => 'Đang xử lý',
                'session_id' => $session_id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($cart as $item) {
                $product = DB::table('tbl_product')
                    ->where('product_id', $item['product_id'])
                    ->select('product_quantity', 'product_name')
                    ->first();

                if (!$product || $product->product_quantity < $item['quantity']) {
                    throw new \Exception('Sản phẩm ' . ($product ? $product->product_name : $item['product_id']) . ' không đủ số lượng tồn kho!');
                }

                $discounted_price = $item['product_price'] * (1 - ($item['product_discount'] ?? 0) / 100);
                $original_price = $item['product_price'];
                DB::table('tbl_order_detail')->insert([
                    'order_id' => $order_id,
                    'product_id' => $item['product_id'],
                    'product_quantity' => $item['quantity'],
                    'product_price' => $discounted_price,
                    'original_price' => $original_price,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::table('tbl_product')
                    ->where('product_id', $item['product_id'])
                    ->decrement('product_quantity', $item['quantity']);
            }

            if ($promotion_code && $promotion_discount > 0) {
                DB::table('tbl_promotion')
                    ->where('code', $promotion_code)
                    ->increment('used_count');
            }

            // // Gửi email thông báo đơn hàng
            $customer_email = DB::table('tbl_customer')->where('customer_id', $customer_id)->value('customer_email');
            if ($customer_email) {
                $orderInfo = [
                    'order_id' => $order_id,
                    'order_total' => $order_total,
                    'shipping_fee' => $shipping_fee,
                ];
                try {
                    Mail::to($customer_email)->send(new OrderSuccessMail($orderInfo, $customer_email));
                    Log::info('Order confirmation email sent', ['email' => $customer_email, 'order_id' => $order_id]);
                } catch (\Exception $e) {
                    Log::error('Failed to send order confirmation email', ['email' => $customer_email, 'error' => $e->getMessage()]);
                }
            }

            Session::forget(['cart', 'promotion_discount', 'promotion_code', 'shipping_id', 'shipping_fee']);
            Session::put('latest_order_id', $order_id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement failed', ['error' => $e->getMessage()]);
            return Redirect::back()->with('error', 'Đặt hàng thất bại: ' . $e->getMessage());
        }

        if ($request->payment_option === 'VNPay') {
            $vnp_TmnCode = '6JFP813J';
            $vnp_HashSecret = 'R9AYAGT6A9M89N99AVPNRSXRKV6DOFTT';
            $vnp_Url = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
            $vnp_ReturnUrl = 'http://localhost/shopbanhang/thank-you';

            if (!$vnp_TmnCode || !$vnp_HashSecret || !$vnp_Url || !$vnp_ReturnUrl) {
                Log::error('VNPay configuration missing');
                return Redirect::back()->with('error', 'Cấu hình VNPay không hợp lệ!');
            }

            $vnp_TxnRef = $order_id . '_' . time();
            $vnp_OrderInfo = 'Thanh toan don hang #' . $order_id;
            $vnp_OrderType = 'billpayment';
            $vnp_Amount = $order_total * 100;
            $vnp_Locale = $request->input('language', 'vn');
            $vnp_BankCode = $request->input('bankCode', '');
            $vnp_IpAddr = $request->ip();
            $vnp_CreateDate = date('YmdHis');

            $inputData = [
                'vnp_Version' => '2.1.0',
                'vnp_TmnCode' => $vnp_TmnCode,
                'vnp_Amount' => $vnp_Amount,
                'vnp_Command' => 'pay',
                'vnp_CreateDate' => $vnp_CreateDate,
                'vnp_CurrCode' => 'VND',
                'vnp_IpAddr' => $vnp_IpAddr,
                'vnp_Locale' => $vnp_Locale,
                'vnp_OrderInfo' => $vnp_OrderInfo,
                'vnp_OrderType' => $vnp_OrderType,
                'vnp_ReturnUrl' => $vnp_ReturnUrl,
                'vnp_TxnRef' => $vnp_TxnRef,
            ];

            if ($vnp_BankCode) {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }

            ksort($inputData);
            $query = "";
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url .= "?" . $query;
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

            DB::table('tbl_payment')->where('payment_id', $payment_id)
                ->update([
                    'vnpay_transaction_id' => $vnp_TxnRef,
                    'vnpay_status' => 'pending',
                ]);

            Log::info('VNPay URL generated: ' . $vnp_Url);
            return Redirect::to($vnp_Url);
        } elseif ($request->payment_option === 'bằng thẻ') {
            return Redirect::to('/payment-card');
        } else {
            return Redirect::to('/thank-you')->with('success', 'Đặt hàng thành công!');
        }
    }
}
