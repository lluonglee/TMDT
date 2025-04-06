<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class EmployeeController extends Controller
{

    // Liệt kê nhân viên
    public function list_employee()
    {
        $employees = DB::table('tbl_employee')->get(); // Lấy tất cả nhân viên
        return view('admin.list_employee', compact('employees'));
    }

    // Tạo nhân viên mới
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email|unique:tbl_employee',
            'employee_password' => 'required|string|min:8',
            'employee_phone' => 'required|string',
        ]);

        // Tạo nhân viên mới
        DB::table('tbl_employee')->insert([
            'employee_name' => $request->employee_name,
            'employee_email' => $request->employee_email,
            'employee_password' => bcrypt($request->employee_password),
            'employee_phone' => $request->employee_phone,
            'status' => 1, // Mặc định là hoạt động
            'role' => $request->role ?? 0, // Mặc định là nhân viên
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Tạo nhân viên thành công!');
    }
    public function store_create()
    {

        return view('admin.create_employee');
    }

    // Cập nhật thông tin nhân viên
    public function update(Request $request, $id)
    {
        // Validate dữ liệu
        $request->validate([
            'employee_name' => 'required|string|max:255',
            'employee_email' => 'required|email',
            'employee_phone' => 'required|string',
        ]);

        // Cập nhật thông tin nhân viên
        DB::table('tbl_employee')->where('employee_id', $id)->update([
            'employee_name' => $request->employee_name,
            'employee_email' => $request->employee_email,
            'employee_phone' => $request->employee_phone,
            'role' => $request->role,
            'updated_at' => now(),
        ]);

        // Nếu mật khẩu được điền, cập nhật mật khẩu
        if ($request->filled('employee_password')) {
            DB::table('tbl_employee')->where('employee_id', $id)->update([
                'employee_password' => bcrypt($request->employee_password),
                'updated_at' => now(),
            ]);
        }

        return redirect('/employees')->with('message', 'Cập nhật nhân viên thành công!');
    }



    // Khóa nhân viên
    public function lock($id)
    {
        DB::table('tbl_employee')->where('employee_id', $id)->update(['status' => 0]); // Đặt trạng thái là bị khóa
        return redirect()->back()->with('message', 'Nhân viên đã bị khóa!');
    }

    // Mở khóa nhân viên
    public function unlock($id)
    {
        DB::table('tbl_employee')->where('employee_id', $id)->update(['status' => 1]); // Đặt trạng thái là hoạt động
        return redirect()->back()->with('message', 'Nhân viên đã được mở khóa!');
    }

    public function edit_employee($id)
    {
        // Lấy thông tin nhân viên cần chỉnh sửa
        $employee = DB::table('tbl_employee')->where('employee_id', $id)->first();

        if (!$employee) {
            return redirect()->back()->with('error', 'Nhân viên không tồn tại');
        }

        // Trả về view chỉnh sửa nhân viên
        return view('admin.edit_employee', compact('employee'));
    }

    // Xóa nhân viên
    public function destroy($id)
    {
        DB::table('tbl_employee')->where('employee_id', $id)->delete(); // Xóa nhân viên
        return redirect()->back()->with('message', 'Xóa nhân viên thành công!');
    }
}