<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->order_id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 10px;
            color: #333;
        }

        h2,
        h4 {
            color: #007BFF;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #E7F1FF;
            text-align: left;
        }

        th {
            background-color: #E7F1FF;
            color: #007BFF;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .discount {
            color: #28A745;
        }

        .total {
            font-weight: bold;
        }

        del {
            color: #999;
        }
    </style>
</head>

<body>
    <h2 class="text-center">HÓA ĐƠN #{{ $order->order_id }}</h2>

    <h4>THÔNG TIN VẬN CHUYỂN</h4>
    <table>
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
        <tr>
            <th>Email:</th>
            <td>{{ $order->shipping_email }}</td>
        </tr>
    </table>

    <!-- <h4>THÔNG TIN THANH TOÁN</h4>
    <table>
        <tr>
            <th>Phương thức thanh toán:</th>
            <td>{{ $order->payment_method == 'bằng thẻ' ? 'Thẻ tín dụng' : 'Tiền mặt' }}</td>
        </tr>

    </table> -->

    <h4>THÔNG TIN ĐƠN HÀNG</h4>
    <table>
        <tr>
            <th>Mã đơn hàng:</th>
            <td>{{ $order->order_id }}</td>
        </tr>
        <tr>
            <th>Ngày đặt:</th>
            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Tổng tiền gốc:</th>
            <td>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
        </tr>
        @if($product_discount_total > 0)
        <tr>
            <th>Giảm giá sản phẩm:</th>
            <td class="discount">-{{ number_format($product_discount_total, 0, ',', '.') }} VNĐ</td>
        </tr>
        @endif
        @if($promotion_discount > 0)
        <tr>
            <th>Giảm giá mã ({{ $order->discount_code }}):</th>
            <td class="discount">-{{ number_format($promotion_discount, 0, ',', '.') }} VNĐ</td>
        </tr>
        @endif
        <tr>
            <th>Thành tiền:</th>
            <td class="total">{{ number_format($order->order_total, 0, ',', '.') }} VNĐ</td>
        </tr>
    </table>

    <h4>CHI TIẾT SẢN PHẨM</h4>
    <table>
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
            $discount_ratio = $total_after_product_discount > 0 ? $promotion_discount / $total_after_product_discount :
            0;
            $total = 0;
            @endphp
            @foreach($order_details as $detail)
            @php
            $original_price = $detail->original_price ?? ($detail->product_price / (1 - ($detail->product_discount /
            100)));
            $price_after_product_discount = $detail->product_price;
            $promotion_discount_per_item = $price_after_product_discount * $discount_ratio;
            $final_price = max(0, $price_after_product_discount - $promotion_discount_per_item);
            $subtotal_item = $final_price * $detail->product_quantity;
            $total += $subtotal_item;
            @endphp
            <tr>
                <td>{{ $detail->product_name }}</td>
                <td>{{ $detail->product_quantity }}</td>
                <td>{{ number_format($original_price, 0, ',', '.') }} VNĐ</td>
                <td>
                    @if($detail->product_discount > 0)
                    <del>{{ number_format($original_price, 0, ',', '.') }} VNĐ</del><br>
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
                <td colspan="5" class="text-right total">Tổng cộng:</td>
                <td class="total">{{ number_format($total, 0, ',', '.') }} VNĐ</td>
            </tr>
        </tbody>
    </table>
</body>

</html>