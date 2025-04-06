@extends('admin_layout')

@section('admin_content')
<h2>Danh sách nhân viên</h2>

@if(Session::has('message'))
<div class="alert alert-success">{{ Session::get('message') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên nhân viên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($employees as $employee)
        <tr>
            <td>{{ $employee->employee_id }}</td>
            <td>{{ $employee->employee_name }}</td>
            <td>{{ $employee->employee_email }}</td>
            <td>{{ $employee->employee_phone }}</td>
            <td>
                @if($employee->status == 1)
                <span class="text-success">Hoạt động</span>
                @else
                <span class="text-danger">Đã khóa</span>
                @endif
            </td>
            <td>
                <!-- <form action="{{ URL('/employees/edit/'.$employee->employee_id) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    
                </form> -->

                <button class="btn btn-warning btn-sm"> <a style="font-size: 25px;"
                        href="{{ url('/employees/edit/'.$employee->employee_id) }}" class="active">
                        <i class="fa fa-pencil-square-o text-success"></i>
                    </a></button>


                @if($employee->status == 1)
                <form action="{{ URL('/employees/lock/'.$employee->employee_id) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Khóa</button>
                </form>
                @else
                <form action="{{ URL('/employees/unlock/'.$employee->employee_id) }}" method="POST"
                    style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Mở khóa</button>
                </form>
                @endif

                <form action="{{ URL('/employees-destroy/'.$employee->employee_id) }}" method="POST"
                    style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection