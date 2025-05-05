@extends('admin_layout')

@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading" style="background-color: #E7F1FF; color: #007BFF;">
                Thông tin chi tiết đơn hàng #{{ $order->order_id }}
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <!-- Thông tin đơn hàng -->
                <h4>Thông Tin Đơn Hàng</h4>
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
                        <th>Thành Tiền</th>
                        <td><strong>{{ number_format($order->order_total, 0, ',', '.') }} VNĐ</strong></td>
                    </tr>
                </table>

                <!-- Form cập nhật trạng thái -->
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
            <header class="panel-heading" style="background-color: #E7F1FF; color: #007BFF;">
                Chi tiết đơn hàng #{{ $order->order_id }}
            </header>
            <div class="panel-body">
                <!-- Thông tin khách hàng -->
                <h4>Thông tin khách hàng</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Họ tên</th>
                        <td>{{ $order->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $order->customer_email }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $order->customer_phone }}</td>
                    </tr>
                </table>

                <!-- Thông tin vận chuyển -->
                <h4>Thông tin vận chuyển</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Tên người nhận</th>
                        <td>{{ $order->shipping_name }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ $order->shipping_address }}</td>
                    </tr>
                    <tr>
                        <th>Số điện thoại</th>
                        <td>{{ $order->shipping_phone }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $order->shipping_email }}</td>
                    </tr>
                </table>

                <!-- Thông tin thanh toán -->
                <!-- <h4>Thông tin thanh toán</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Phương thức</th>
                        <td>{{ $order->payment_method == 'bằng thẻ' ? 'Thẻ tín dụng' : 'Tiền mặt' }}</td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>{{ $order->payment_status }}</td>
                    </tr>
                </table> -->

                <!-- Chi tiết sản phẩm -->
                <h4>Chi tiết sản phẩm</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá gốc</th>
                            <th>Giá sau giảm sản phẩm</th>
                            <th>Giá sau mã khuyến mãi</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $total_after_product_discount = $subtotal - $product_discount_total;
                        $promotion_discount = $order->discount_amount ?? 0;
                        $discount_ratio = $total_after_product_discount > 0 ? $promotion_discount /
                        $total_after_product_discount : 0;
                        $total = 0;
                        @endphp
                        @foreach($order_details as $detail)
                        @php
                        $original_price = $detail->original_price ?? ($detail->product_price / (1 -
                        ($detail->product_discount / 100)));
                        $price_after_product_discount = $detail->product_price;
                        $promotion_discount_per_item = $price_after_product_discount * $discount_ratio;
                        $final_price = $price_after_product_discount - $promotion_discount_per_item;
                        $subtotal_item = $final_price * $detail->product_quantity;
                        $total += $subtotal_item;
                        @endphp
                        <tr>
                            <td>{{ $detail->product_name }}</td>
                            <td>{{ $detail->product_quantity }}</td>
                            <td>{{ number_format($original_price, 0, ',', '.') }} VNĐ</td>
                            <td>
                                @if($detail->product_discount > 0)
                                {{ number_format($price_after_product_discount, 0, ',', '.') }} VNĐ
                                (-{{ $detail->product_discount }}%)
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
                            <td>{{ number_format($subtotal_item, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong>Tổng cộng:</strong></td>
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

<style>
    /* Tổng thể */
    .panel-heading {
        background-color: #f0f8ff;
        color: #004085;
        font-size: 18px;
        font-weight: bold;
        padding: 12px 20px;
    }

    /* Tiêu đề bảng */
    .table th {
        background-color: #f0f8ff;
        color: #004085;
        text-align: left;
        vertical-align: middle;
        padding: 10px;
    }

    /* Dữ liệu bảng */
    .table td {
        border: 1px solid #dee2e6;
        padding: 10px;
        vertical-align: middle;
    }

    /* Các giá trị tiền tệ canh phải */
    .table td:nth-child(n+3),
    .table th:nth-child(n+3) {
        text-align: right;
    }

    /* Các tiêu đề section */
    h4 {
        margin-top: 30px;
        font-weight: 600;
        border-left: 5px solid #007BFF;
        padding-left: 10px;
        color: #333;
    }

    .table th,
    .table td {
        color: #000 !important;
    }

    /* Nút */
    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #0056B3;
        border-color: #0056B3;
    }

    .btn-success {
        background-color: #28A745;
        border-color: #28A745;
        font-weight: 500;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
    }

    /* Alert */
    .alert-success {
        background-color: #e2f0d9;
        color: #155724;
        font-weight: bold;
    }

    /* Select */
    select.form-control {
        max-width: 300px;
    }

    /* Tổng cộng in đậm, nổi bật */
    .table tfoot td {
        font-weight: bold;
        background-color: #f9f9f9;
    }
</style>

@endsection