@extends('layout')
@section('content')
<div class="container">
    <h2 style="color: #007BFF;">Chi Tiết Đơn Hàng</h2>

    @if(Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <!-- Thông tin đơn hàng -->
    <h3>Thông Tin Đơn Hàng</h3>
    <table class="table table-bordered">
        <tr>
            <th>Mã Đơn Hàng</th>
            <td>{{ $order->order_id }}</td>
        </tr>
        <tr>
            <th>Ngày Đặt</th>
            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Trạng Thái</th>
            <td>{{ $order->order_status }}</td>
        </tr>
        <tr>
            <th>Tổng Tiền Gốc</th>
            <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
        </tr>
        @if($product_discount_total > 0)
        <tr>
            <th>Giảm Giá Sản Phẩm</th>
            <td style="color: #28A745;">-{{ number_format($product_discount_total, 0, ',', '.') }} VNĐ</td>
        </tr>
        @endif
        @if($order->discount_amount > 0)
        <tr>
            <th>Giảm Giá Mã ({{ $order->discount_code }})</th>
            <td style="color: #28A745;">-{{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</td>
        </tr>
        @endif
        <tr>
            <th>Phí Ship</th>
            <td>{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</td>
        </tr>
        <tr>
            <th>Thành Tiền</th>
            <td><strong>{{ number_format($order->order_total, 0, ',', '.') }} VNĐ</strong></td>
        </tr>
    </table>

    <!-- Thông tin vận chuyển -->
    @if($shipping)
    <h3>Thông Tin Vận Chuyển</h3>
    <table class="table table-bordered">
        <tr>
            <th>Tên Người Nhận</th>
            <td>{{ $shipping->shipping_name }}</td>
        </tr>
        <tr>
            <th>Địa Chỉ</th>
            <td>{{ $shipping->shipping_address }}</td>
        </tr>
        <tr>
            <th>Số Điện Thoại</th>
            <td>{{ $shipping->shipping_phone }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $shipping->shipping_email }}</td>
        </tr>
    </table>
    @endif

    <!-- Thông tin thanh toán -->
    @if($payment)
    <h3>Thông Tin Thanh Toán</h3>
    <table class="table table-bordered">
        <tr>
            <th>Phương Thức</th>
            <td>{{ $payment->payment_method == 'bằng thẻ' ? 'Thẻ tín dụng' : 'Tiền mặt' }}</td>
        </tr>
        <tr>
            <th>Trạng Thái</th>
            <td>{{ $payment->payment_status }}</td>
        </tr>
    </table>
    @endif

    <!-- Sản phẩm đã mua -->
    <h3>Sản Phẩm Đã Mua</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Hình Ảnh</th>
                <th>Tên Sản Phẩm</th>
                <th>Số Lượng</th>
                <th>Giá Gốc</th>
                <th>Giá Sau Giảm Sản Phẩm</th>
                <th>Giá Sau Mã Khuyến Mãi</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            @php
            // Tính tổng giá trị sản phẩm sau giảm giá sản phẩm
            $total_after_product_discount = $subtotal - $product_discount_total;
            // Tỷ lệ phân bổ giảm giá mã khuyến mãi
            $promotion_discount = $order->discount_amount ?? 0;
            $discount_ratio = $total_after_product_discount > 0 ? $promotion_discount / $total_after_product_discount :
            0;
            @endphp
            @foreach($order_details as $item)
            @php
            $original_price = $item->original_price ?? ($item->product_price / (1 - ($item->product_discount / 100)));
            $price_after_product_discount = $item->product_price;
            // Phân bổ giảm giá mã khuyến mãi
            $promotion_discount_per_item = $price_after_product_discount * $discount_ratio;
            $final_price = $price_after_product_discount - $promotion_discount_per_item;
            $total_item_price = $final_price * $item->product_quantity;
            @endphp
            <tr>
                <td><img src="{{ asset($item->product_image) }}" width="50" alt="{{ $item->product_name }}"></td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->product_quantity }}</td>
                <td>
                    {{ number_format($original_price, 0, ',', '.') }} VNĐ
                </td>
                <td>
                    @if($item->product_discount > 0)
                    {{ number_format($price_after_product_discount, 0, ',', '.') }} VNĐ
                    (-{{ $item->product_discount }}%)
                    @else
                    {{ number_format($price_after_product_discount, 0, ',', '.') }} VNĐ
                    @endif
                </td>
                <td>
                    @if($promotion_discount > 0)
                    {{ number_format($final_price, 0, ',', '.') }} VNĐ
                    @else
                    {{ number_format($price_after_product_discount, 0, ',', '.') }} VNĐ
                    @endif
                </td>
                <td>{{ number_format($total_item_price, 0, ',', '.') }} VNĐ</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ url('/order-history') }}" class="btn btn-primary">Quay Lại</a>
</div>

<style>
    .table th {
        background-color: #E7F1FF;
        color: #007BFF;
    }

    .table td {
        border: 1px solid #E7F1FF;
    }

    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
    }

    .btn-primary:hover {
        background-color: #0056B3;
        border-color: #0056B3;
    }

    del {
        color: #999;
    }
</style>
@endsection