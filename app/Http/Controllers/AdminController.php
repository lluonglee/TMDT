<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpParser\Node\Stmt\Echo_;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    public function AuthLogin()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id) {
            return  Redirect::to('dashboard');
        } else {
            return Redirect::to('admin')->send();
        }
    }
    public function index()
    {
        return view('admin_login');
    }

    public function show_dashboard()
    {
        $this->AuthLogin();

        return view('admin.dashboard');
    }


    public function dashboard(Request $request)
    {
        $admin_email = $request->input('admin_email');
        $admin_password = md5($request->input('admin_password')); // hoặc dùng Hash::make() nếu lưu bcrypt

        $admin = DB::table('tbl_admin')
            ->where('admin_email', $admin_email)
            ->where('admin_password', $admin_password)
            ->first();

        if ($admin) {
            Session::put('admin_name', $admin->admin_name);
            Session::put('admin_id', $admin->admin_id);
            return Redirect::to('/dashboard');
        } else {
            Session::put('message', 'Sai email hoặc mật khẩu');
            return Redirect::to('/admin');
        }
    }

    public function logout()
    {
        $this->AuthLogin();
        Session::forget('admin_name');
        Session::forget('admin_id');
        return Redirect::to('/admin')->with('message', 'Bạn đã đăng xuất thành công!');
    }
}
