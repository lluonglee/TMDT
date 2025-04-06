@extends('admin_layout')
@section('admin_content')
<h2>Chỉnh sửa thông tin nhân viên</h2>

<form action="{{ URL('/employees-update/'.$employee->employee_id) }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="employee_name">Tên nhân viên</label>
        <input type="text" class="form-control" id="employee_name" name="employee_name"
            value="{{ $employee->employee_name }}" required>
    </div>
    <div class="form-group">
        <label for="employee_email">Email</label>
        <input type="email" class="form-control" id="employee_email" name="employee_email"
            value="{{ $employee->employee_email }}" required>
    </div>
    <div class="form-group">
        <label for="employee_phone">Số điện thoại</label>
        <input type="text" class="form-control" id="employee_phone" name="employee_phone"
            value="{{ $employee->employee_phone }}" required>
    </div>
    <div class="form-group">
        <label for="role">Vai trò</label>
        <select class="form-control" id="role" name="role" required>
            <option value="0" {{ $employee->role == 0 ? 'selected' : '' }}>Nhân viên</option>
            <option value="1" {{ $employee->role == 1 ? 'selected' : '' }}>Quản trị viên</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>


@endsection