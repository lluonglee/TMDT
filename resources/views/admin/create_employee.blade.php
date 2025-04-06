<!-- resources/views/admin/create_employee.blade.php -->
@extends('admin_layout')

@section('admin_content')
<h2>Tạo nhân viên mới</h2>

@if(Session::has('message'))
<div class="alert alert-success">{{ Session::get('message') }}</div>
@endif

<form action="{{ url('/employees-store') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="employee_name">Tên nhân viên</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name" required>
    </div>
    <div class="form-group">
        <label for="employee_email">Email</label>
        <input type="email" class="form-control" id="employee_email" name="employee_email" required>
    </div>
    <div class="form-group">
        <label for="employee_password">Mật khẩu</label>
        <input type="password" class="form-control" id="employee_password" name="employee_password" required>
    </div>
    <div class="form-group">
        <label for="employee_phone">Số điện thoại</label>
        <input type="text" class="form-control" id="employee_phone" name="employee_phone" required>
    </div>
    <div class="form-group">
        <label for="role">Vai trò</label>
        <select class="form-control" id="role" name="role">
            <option value="0">Nhân viên</option>
            <option value="1">Quản trị viên</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Tạo nhân viên</button>
</form>

@endsection