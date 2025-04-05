<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2 class="text-center">HOA DON #{{ $order->order_id }}</h2>

    <h4>THONG TIN KHACH HANG</h4>
    <table>
        <tr>
            <th>HO TEN:</th>
            <td>{{ $order->customer_name }}</td>
        </tr>
        <tr>
            <th>Email:</th>
            <td>{{ $order->customer_email }}</td>
        </tr>
        <tr>
            <th>SO DIEN THOAI:</th>
            <td>{{ $order->customer_phone }}</td>
        </tr>
    </table>

    <h4>ThONG TIN VAN CHUYEN</h4>
    <table>
        <tr>
            <th>TEN NGUOI NHAN:</th>
            <td>{{ $order->shipping_name }}</td>
        </tr>
        <tr>
            <th>DIA CHI:</th>
            <td>{{ $order->shipping_address }}</td>
        </tr>
        <tr>
            <th>SO DIEN THOAI:</th>
            <td>{{ $order->shipping_phone }}</td>
        </tr>

    </table>

    <h4>THONG TIN THANH TOAN</h4>
    <table>
        <tr>
            <th>PhUONG THUC THANH TOAN:</th>
            <td>{{ $order->payment_method }}</td>
        </tr>
        <tr>
            <th>TrANG THAI THANH TOAN:</th>
            <td>{{ $order->payment_status }}</td>
        </tr>
    </table>

    <h4>Chi TIET</h4>
    <table>
        <thead>
            <tr>
                <th>TEN SAN PHAM</th>
                <th>SO LUONG</th>
                <th>GIA </th>
                <th>ThANH TIEN</th>
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

</body>

</html>