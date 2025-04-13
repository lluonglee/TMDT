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
    // public function register(Request $request)
    // {
    //     $data = [
    //         'customer_name' => $request->input('customer_name'),
    //         'customer_email' => $request->input('customer_email'),
    //         'customer_password' => Hash::make($request->input('customer_password')), // Mã hóa an toàn
    //         'customer_phone' => $request->input('customer_phone'),
    //     ];

    //     $customer_id = DB::table('tbl_customer')->insertGetId($data);

    //     Session::put('customer_id', $customer_id);
    //     Session::put('customer_name', $request->input('customer_name'));

    //     return Redirect::to('/checkout');
    // }
    public function register(Request $request)
    {
        // Kiểm tra dữ liệu đầu vào với validation
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|unique:tbl_customer,customer_email',
            'customer_password' => 'required|string|min:3|confirmed',  // Ràng buộc mật khẩu ít nhất 3 ký tự và phải khớp với xác nhận
            'customer_phone' => 'required|string|max:15', // Giới hạn số điện thoại tối đa 15 ký tự
        ]);

        // Nếu validation thành công, tiếp tục xử lý dữ liệu
        $data = [
            'customer_name' => $request->input('customer_name'),
            'customer_email' => $request->input('customer_email'),
            'customer_password' => Hash::make($request->input('customer_password')), // Mã hóa an toàn
            'customer_phone' => $request->input('customer_phone'),
        ];

        // Chèn thông tin khách hàng vào cơ sở dữ liệu
        $customer_id = DB::table('tbl_customer')->insertGetId($data);

        // Lưu thông tin khách hàng vào session
        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->input('customer_name'));

        // Chuyển hướng tới trang checkout
        return Redirect::to('/checkout');
    }


    // Xử lý đăng nhập khách hàng
    // public function login(Request $request)
    // {
    //     $email = $request->input('email_account');
    //     $password = $request->input('password_account');

    //     $customer = DB::table('tbl_customer')
    //         ->where('customer_email', $email)
    //         ->first();

    //     if ($customer && Hash::check($password, $customer->customer_password)) {
    //         Session::put('customer_id', $customer->customer_id);
    //         Session::put('customer_name', $customer->customer_name);
    //         return Redirect::to('/checkout');
    //     } else {
    //         return Redirect::to('/customer/login')->with('error', 'Sai tài khoản hoặc mật khẩu!');
    //     }
    // }
    public function login(Request $request)
    {
        $email = $request->input('email_account');
        $password = $request->input('password_account');

        $admin_email = $request->input('email_account');
        $admin_password = md5($request->input('password_account'));

        $employee = DB::table('tbl_employee')
            ->where('employee_email', $email)
            ->first();

        $customer = DB::table('tbl_customer')
            ->where('customer_email', $email)
            ->first();

        $admin = DB::table('tbl_admin')
            ->where('admin_email', $admin_email)
            ->where('admin_password', $admin_password)
            ->first();



        if ($employee) {
            if ($employee->status == 0) {
                return Redirect::to('/customer/login')->with('error', 'Tài khoản của bạn đã bị khóa.');
            }

            if (Hash::check($password, $employee->employee_password)) {
                Session::put('employee_id', $employee->employee_id);
                Session::put('employee_name', $employee->employee_name);
                Session::put('role', $employee->role);
                Session::put('permissions', json_decode($employee->permissions, true) ?? []); // Lưu phân quyền
                return Redirect::to('/dashboard');
            }
        }

        if ($customer) {
            if ($customer->status == 0) {
                return Redirect::to('/customer/login')->with('error', 'Tài khoản của bạn đã bị khóa.');
            }

            if (Hash::check($password, $customer->customer_password)) {
                Session::put('customer_id', $customer->customer_id);
                Session::put('customer_name', $customer->customer_name);
                return Redirect::to('/checkout');
            }
        }
        if ($admin) {
            Session::put('admin_name', $admin->admin_name);
            Session::put('admin_id', $admin->admin_id);
            return Redirect::to('/dashboard');
        }

        return Redirect::to('/customer/login')->with('error', 'Sai tài khoản hoặc mật khẩu!');
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
