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
    <div class="form-group">
        <label>Phân quyền</label><br>
        @php $permissions = json_decode($employee->permissions, true) ?? []; @endphp
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="category_product" class="form-check-input"
                {{ in_array('category_product', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý danh mục sản phẩm</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="brand_product" class="form-check-input"
                {{ in_array('brand_product', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý thương hiệu sản phẩm</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="manage_product" class="form-check-input"
                {{ in_array('manage_product', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý sản phẩm</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="manage_order" class="form-check-input"
                {{ in_array('manage_order', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý đơn hàng</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="manage_customer" class="form-check-input"
                {{ in_array('manage_customer', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý khách hàng</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="manage_comment" class="form-check-input"
                {{ in_array('manage_comment', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý đánh giá</label>
        </div>

        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="manage_promotion" class="form-check-input"
                {{ in_array('manage_promotion', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý khuyến mãi</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="permissions[]" value="manage_shipping" class="form-check-input"
                {{ in_array('manage_shipping', $permissions) ? 'checked' : '' }}>
            <label class="form-check-label">Quản lý giao hàng</label>
        </div>

    </div>
    <button type="submit" class="btn btn-primary">Cập nhật</button>
</form>


@endsection