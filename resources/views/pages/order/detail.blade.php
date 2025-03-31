@extends('layout')

@section('content')
<h2>Chi Tiết Đơn Hàng</h2>

<table class="table table-bordered">
    <tr>
        <th>Mã Đơn Hàng:</th>
        <td>{{ $order->order_id }}</td>
    </tr>
    <tr>
        <th>Ngày Đặt:</th>
        <td>{{ $order->created_at }}</td>
    </tr>
    <tr>
        <th>Tổng Tiền:</th>
        <td>{{ number_format($order->order_total, 0, ',', '.') }} VNĐ</td>
    </tr>
    <tr>
        <th>Trạng Thái:</th>
        <td>{{ $order->order_status }}</td>
    </tr>
</table>

<h3>Sản Phẩm Đã Mua</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Hình Ảnh</th>
            <th>Tên Sản Phẩm</th>
            <th>Số Lượng</th>
            <th>Giá</th>
            <th>Tổng</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($order_details as $item)
        <tr>
            <td><img src=" {{ asset($item->product_image) }}" width="50"></td>
            <td>{{ $item->product_name }}</td>
            <td>{{ $item->product_quantity }}</td>
            <td>{{ number_format($item->product_price, 0, ',', '.') }} VNĐ</td>
            <td>{{ number_format($item->product_price * $item->product_quantity, 0, ',', '.') }} VNĐ</td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{ url('/order-history') }}" class="btn btn-primary">Quay Lại</a>
@endsection