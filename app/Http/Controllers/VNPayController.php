<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    public function callback(Request $request)
    {
        Log::info('VNPay callback received', $request->all());

        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_SecureHash = $request->query('vnp_SecureHash');
        $inputData = $request->query();

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $query = http_build_query($inputData);
        $hashData = $query;
        $calculatedHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);

        $vnp_TxnRef = $request->query('vnp_TxnRef');
        $vnp_ResponseCode = $request->query('vnp_ResponseCode');
        $order_id = explode('_', $vnp_TxnRef)[0];

        if ($vnp_SecureHash === $calculatedHash) {
            if ($vnp_ResponseCode === '00') {
                DB::table('tbl_payment')->where('vnpay_transaction_id', $vnp_TxnRef)
                    ->update([
                        'vnpay_status' => 'success',
                        'payment_status' => 'Thành công',
                    ]);
                DB::table('tbl_order')->where('order_id', $order_id)
                    ->update(['order_status' => 'Hoàn thành']);
                Session::flash('success', 'Thanh toán VNPay thành công!');
            } else {
                DB::table('tbl_payment')->where('vnpay_transaction_id', $vnp_TxnRef)
                    ->update([
                        'vnpay_status' => 'failed',
                        'payment_status' => 'Thất bại',
                    ]);
                Session::flash('error', 'Thanh toán VNPay thất bại. Mã lỗi: ' . $vnp_ResponseCode);
            }
        } else {
            Session::flash('error', 'Chữ ký VNPay không hợp lệ.');
        }

        return Redirect::to('/checkout/result');
    }
}