<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class CustomerController extends Controller
{
    // Hiển thị trang đăng nhập & đăng ký
    public function showLogin()
    {
        return view('pages.checkout.login');
    }

    // Trang đăng ký
    public function showRegister()
    {
        return view('pages.checkout.register');
    }

    // Xử lý đăng ký khách hàng
    public function register(Request $request)
    {
        $data = [
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_password' => Hash::make($request->input('customer_password')), // Mã hóa an toàn
            'customer_phone' => $request->input('customer_phone'),
        ];

        $customer_id = DB::table('tbl_customer')->insertGetId($data);

        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->input('customer_name'));

        return Redirect::to('/checkout');
    }

    // Xử lý đăng nhập khách hàng
    public function login(Request $request)
    {
        $email = $request->input('email_account');
        $password = $request->input('password_account');

        $customer = DB::table('tbl_customer')
            ->where('customer_email', $email)
            ->first();

        if ($customer && Hash::check($password, $customer->customer_password)) {
            Session::put('customer_id', $customer->customer_id);
            Session::put('customer_name', $customer->customer_name);
            return Redirect::to('/checkout');
        } else {
            return Redirect::to('/customer/login')->with('error', 'Sai tài khoản hoặc mật khẩu!');
        }
    }

    public function check_out()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();
        return view('pages.checkout.show_checkout')->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,

        ]);;
    }


    // Đăng xuất khách hàng
    public function logout()
    {
        Session::flush();
        return Redirect::to('/');
    }

    //shipping
    public function saveShipping(Request $request)
    {
        $data = [
            'shipping_email' => $request->shipping_email,
            'shipping_name' => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_phone' => $request->shipping_phone,
        ];

        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);

        Session::put('shipping_id', $shipping_id);

        return Redirect::to('/payment');
    }
    public function payment(Request $request)
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
            ->orderBy('brand_id', 'desc')
            ->get();

        return view('pages.checkout.payment')->with([
            'categories' => $categories, // Truyền đúng biến
            'brands' => $brands,

        ]);
    }
}
