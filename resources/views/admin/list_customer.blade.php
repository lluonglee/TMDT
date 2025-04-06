@extends('admin_layout')
@section('admin_content')

<h2>Quản lý tài khoản khách hàng</h2>

@if(Session::has('message'))
<div class="alert alert-success">{{ Session::get('message') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Họ tên</th>
            <th>Email</th>
            <th>SĐT</th>
            <th>Trạng thái</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customers as $cus)
        <tr>
            <td>{{ $cus->customer_id }}</td>
            <td>{{ $cus->customer_name }}</td>
            <td>{{ $cus->customer_email }}</td>
            <td>{{ $cus->customer_phone }}</td>
            <td>
                @if($cus->status == 1)
                <span class="text-success">Hoạt động</span>
                @else
                <span class="text-danger">Đã khóa</span>
                @endif
            </td>
            <td>
                @if($cus->status == 1)
                <form action="{{ url('/customers/lock/'.$cus->customer_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-sm">Khóa</button>
                </form>
                @else
                <form action="{{ url('/customers/unlock/'.$cus->customer_id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Mở khóa</button>
                </form>
                @endif

                <form action="{{ url('/customers/delete/'.$cus->customer_id) }}" method="POST" style="display:inline;"
                    onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection