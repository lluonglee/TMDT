@extends('layout')

@section('content')
<h2>Lịch Sử Đơn Hàng</h2>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Mã Đơn Hàng</th>
            <th>Ngày Đặt</th>
            <th>Tổng Tiền</th>
            <th>Trạng Thái</th>
            <th>Chi Tiết</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order->order_id }}</td>
            <td>{{ $order->created_at }}</td>
            <td>{{ number_format($order->order_total, 0, ',', '.') }} VNĐ</td>
            <td>{{ $order->order_status }}</td>
            <td><a href="{{ url('/order-detail/'.$order->order_id) }}" class="btn btn-info">Xem</a></td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection