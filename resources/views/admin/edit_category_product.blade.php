@extends('admin_layout')

@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Cập nhật thông tin nhân viên
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    {{ Session::get('message') }}
                </div>
                @endif

                <div class="position-center">
                    <form role="form" action="{{ url('/employees-update/'.$employee->employee_id) }}" method="POST">
                        {{ csrf_field() }}
                        @method('POST')
                        <!-- Bạn có thể dùng phương thức POST hoặc PUT ở đây -->

                        <div class="form-group">
                            <label for="employee_name">Tên nhân viên</label>
                            <input type="text" class="form-control" name="employee_name" id="employee_name"
                                value="{{ $employee->employee_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="employee_email">Email</label>
                            <input type="email" class="form-control" name="employee_email" id="employee_email"
                                value="{{ $employee->employee_email }}" required>
                        </div>

                        <div class="form-group">
                            <label for="employee_phone">Số điện thoại</label>
                            <input type="text" class="form-control" name="employee_phone" id="employee_phone"
                                value="{{ $employee->employee_phone }}" required>
                        </div>

                        <div class="form-group">
                            <label for="role">Vai trò</label>
                            <select class="form-control" name="role" id="role">
                                <option value="0" {{ $employee->role == 0 ? 'selected' : '' }}>Nhân viên</option>
                                <option value="1" {{ $employee->role == 1 ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <address></address>
                            <label for="employee_password">Mật khẩu</label>
                            <input type="password" class="form-control" name="employee_password" id="employee_password">
                            <small>(Để trống nếu không muốn thay đổi mật khẩu)</small>
                        </div>

                        <button type="submit" class="btn btn-info">Cập nhật nhân viên</button>
                    </form>
                </div>

            </div>
        </section>
    </div>
</div>
@endsection