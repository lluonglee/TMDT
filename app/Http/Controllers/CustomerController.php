<?php

namespace App\Http\Controllers;

use App\Mail\PasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


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
                return Redirect::to('/');
            }
        }
        if ($admin) {
            Session::put('admin_name', $admin->admin_name);
            Session::put('admin_id', $admin->admin_id);
            return Redirect::to('/dashboard');
        }

        return Redirect::to('/customer/login')->with('error', 'Sai tài khoản hoặc mật khẩu!');
    }
    // Hiển thị form quên mật khẩu
    public function showForgotPasswordForm()
    {
        return view('pages.checkout.forgot-password');
    }
    //gửi mail
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Kiểm tra email tồn tại trong bảng customer, employee hoặc admin
        $customer = DB::table('tbl_customer')->where('customer_email', $request->email)->first();
        $employee = DB::table('tbl_employee')->where('employee_email', $request->email)->first();
        $admin = DB::table('tbl_admin')->where('admin_email', $request->email)->first();

        if (!$customer && !$employee && !$admin) {
            return Redirect::back()->with('error', 'Email không tồn tại.');
        }

        // Tạo token và lưu vào bảng password_resets
        $token = Str::random(60);
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]
        );

        // Tạo URL đặt lại mật khẩu
        $resetUrl = url('/reset-password?token=' . $token . '&email=' . urlencode($request->email));

        // Gửi email
        try {
            Mail::to($request->email)->send(new PasswordReset($resetUrl));
        } catch (\Exception $e) {
            return Redirect::back()->with('error', 'Không thể gửi email. Vui lòng thử lại sau.');
        }

        return Redirect::back()->with('success', 'Liên kết đặt lại mật khẩu đã được gửi đến email của bạn.');
    }
    // Hiển thị form đặt lại mật khẩu
    public function showResetPasswordForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        // Kiểm tra token và email
        $reset = DB::table('password_resets')
            ->where('email', $email)
            ->where('token', $token)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return Redirect::to('/customer/login')->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
        }

        return view('pages.checkout.reset-password', compact('token', 'email'));
    }

    // Xử lý đặt lại mật khẩu
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'token' => 'required',
        ]);

        // Kiểm tra token và email
        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return Redirect::to('/customer/login')->with('error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.');
        }

        // Kiểm tra email thuộc bảng nào và cập nhật mật khẩu
        $customer = DB::table('tbl_customer')->where('customer_email', $request->email)->first();
        $employee = DB::table('tbl_employee')->where('employee_email', $request->email)->first();
        $admin = DB::table('tbl_admin')->where('admin_email', $request->email)->first();

        if ($customer) {
            DB::table('tbl_customer')
                ->where('customer_email', $request->email)
                ->update(['customer_password' => Hash::make($request->password)]);
        } elseif ($employee) {
            DB::table('tbl_employee')
                ->where('employee_email', $request->email)
                ->update(['employee_password' => Hash::make($request->password)]);
        } elseif ($admin) {
            DB::table('tbl_admin')
                ->where('admin_email', $request->email)
                ->update(['admin_password' => md5($request->password)]); // Lưu ý: Nên dùng Hash::make
        } else {
            return Redirect::to('/customer/login')->with('error', 'Email không tồn tại.');
        }

        // Xóa token sau khi đặt lại mật khẩu
        DB::table('password_resets')->where('email', $request->email)->delete();

        return Redirect::to('/customer/login')->with('success', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập.');
    }



    // public function check_out()
    // {
    //     $categories = DB::table('tbl_category_product')
    //         ->where('category_status', '1') // Chỉ lấy danh mục đang hiển thị
    //         ->orderBy('category_id', 'desc')
    //         ->get();

    //     $brands = DB::table('tbl_brand')
    //         ->where('brand_status', '1') // Chỉ lấy thương hiệu đang hiển thị
    //         ->orderBy('brand_id', 'desc')
    //         ->get();
    //     return view('pages.checkout.show_checkout')->with([
    //         'categories' => $categories, // Truyền đúng biến
    //         'brands' => $brands,

    //     ]);;
    // }
    public function check_out()
    {
        $categories = DB::table('tbl_category_product')
            ->where('category_status', '1')
            ->orderBy('category_id', 'desc')
            ->get();

        $brands = DB::table('tbl_brand')
            ->where('brand_status', '1')
            ->orderBy('brand_id', 'desc')
            ->get();

        $provinces = DB::table('tbl_tinhthanhpho')
            ->orderBy('name')
            ->get();

        return view('pages.checkout.show_checkout')->with([
            'categories' => $categories,
            'brands' => $brands,
            'provinces' => $provinces,
        ]);
    }


    // Đăng xuất khách hàng
    public function logout()
    {
        Session::flush();
        return Redirect::to('/');
    }

    //shipping
    // public function saveShipping(Request $request)
    // {
    //     $data = [
    //         'shipping_email' => $request->shipping_email,
    //         'shipping_name' => $request->shipping_name,
    //         'shipping_address' => $request->shipping_address,
    //         'shipping_phone' => $request->shipping_phone,
    //     ];

    //     $shipping_id = DB::table('tbl_shipping')->insertGetId($data);

    //     Session::put('shipping_id', $shipping_id);

    //     return Redirect::to('/payment');
    // }
    public function saveShipping(Request $request)
    {
        // Lấy phí ship từ shipping_fees dựa trên matp và maqh
        $matp = $request->matp;
        $maqh = $request->maqh ?: null;
        $shipping_fee = 0;

        if ($matp) {
            $query = DB::table('shipping_fees')->where('matp', $matp);
            if ($maqh) {
                $query->where('maqh', $maqh);
            } else {
                $query->whereNull('maqh');
            }
            $fee_record = $query->select('fee')->first();
            $shipping_fee = $fee_record ? $fee_record->fee : 0;
        } else {
        }

        $data = [
            'shipping_email' => $request->shipping_email,
            'shipping_name' => $request->shipping_name,
            'shipping_address' => $request->shipping_address,
            'shipping_phone' => $request->shipping_phone,
            'matp' => $matp,
            'maqh' => $maqh,
            'shipping_fee' => $shipping_fee,
        ];

        $shipping_id = DB::table('tbl_shipping')->insertGetId($data);


        Session::put('shipping_id', $shipping_id);
        Session::put('shipping_fee', $shipping_fee);

        return Redirect::to('/show-cart');
    }


    public function payment()
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
