@extends('admin_layout')
@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Thông tin chi tiết đơn hàng #{{ $order->order_id }}</header>
            <div class="panel-body">

                @if(Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <!-- Thông tin khách hàng, sản phẩm và thanh toán ở đây -->

                <form action="{{ url('/update-order-status/'.$order->order_id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="order_status">Trạng thái đơn hàng</label>
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="Đang xử lý" {{ $order->order_status == 'Đang xử lý' ? 'selected' : '' }}>Đang
                                xử lý</option>
                            <option value="Hoàn thành" {{ $order->order_status == 'Hoàn thành' ? 'selected' : '' }}>Hoàn
                                thành</option>
                            <option value="Đã hủy" {{ $order->order_status == 'Đã hủy' ? 'selected' : '' }}>Đã hủy
                            </option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Cập nhật trạng thái</button>
                </form>

            </div>
        </section>
    </div>
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Chi tiết đơn hàng #{{ $order->order_id }}</header>
            <div class="panel-body">

                <h4>Thông tin khách hàng</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Họ tên:</th>
                        <td>{{ $order->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $order->customer_email }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại:</th>
                        <td>{{ $order->customer_phone }}</td>
                    </tr>
                </table>

                <h4>Thông tin vận chuyển</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Tên người nhận:</th>
                        <td>{{ $order->shipping_name }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ:</th>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại:</th>
                        <td>{{ $order->shipping_phone }}</td>
                    </tr>

                </table>

                <h4>Thông tin thanh toán</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Phương thức:</th>
                        <td>{{ $order->payment_method }}</td>
                    </tr>

                </table>

                <h4>Chi tiết sản phẩm</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0; @endphp
                        @foreach($order_details as $detail)
                        @php
                        $subtotal = $detail->product_price * $detail->product_quantity;
                        $total += $subtotal;
                        @endphp
                        <tr>
                            <td>{{ $detail->product_name }}</td>
                            <td>{{ $detail->product_quantity }}</td>
                            <td>{{ number_format($detail->product_price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-right"><strong>Tổng cộng:</strong></td>
                            <td><strong>{{ number_format($total, 0, ',', '.') }} VNĐ</strong></td>
                        </tr>
                    </tbody>
                </table>

                <a href="{{ URL('/manage-order') }}" class="btn btn-primary">Quay lại</a>
                <a href="{{ url('/print-invoice/'.$order->order_id) }}" class="btn btn-success" target="_blank">
                    <i class="fa fa-print"></i> In Hóa Đơn
                </a>
            </div>
        </section>
    </div>
</div>
@endsection