@extends('layout')
@section('content')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{ URL('/') }}">Trang Chủ</a></li>
                <li class="active">Thanh toán giỏ hàng</li>
            </ol>
        </div>
        <div class="review-payment">
            <h2>Xem lại giỏ hàng</h2>
        </div>
        @if(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @endif
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Hình ảnh</td>
                        <td class="description">Mô tả</td>
                        <td class="price">Giá gốc</td>
                        <td class="price">Giảm giá SP</td>
                        <td class="price">Giá sau KM</td>
                        <td class="quantity">Số lượng</td>
                        <td class="total">Tổng tiền</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @if(Session::has('cart') && count(Session::get('cart')) > 0)
                    @php
                    $subtotal = 0;
                    $product_discount_total = 0;
                    $promotion_discount = Session::get('promotion_discount', 0);
                    $promotion_code = Session::get('promotion_code', '');
                    $shipping_fee = Session::get('shipping_fee', 0);
                    foreach (Session::get('cart') as $item) {
                    $discount_percentage = $item['product_discount'] ?? 0;
                    $discounted_price = $item['product_price'] * (1 - $discount_percentage / 100);
                    $subtotal += $item['product_price'] * $item['quantity'];
                    $product_discount_total += ($item['product_price'] - $discounted_price) * $item['quantity'];
                    }
                    $total_after_product_discount = $subtotal - $product_discount_total;
                    $promotion_discount = min($promotion_discount, $total_after_product_discount);
                    $total_after = max(0, $total_after_product_discount - $promotion_discount + $shipping_fee);
                    @endphp
                    @foreach(Session::get('cart') as $item)
                    @php
                    $discount_percentage = $item['product_discount'] ?? 0;
                    $discounted_price = $item['product_price'] * (1 - $discount_percentage / 100);
                    $product_discount = $item['product_price'] - $discounted_price;
                    $promotion_discount_per_item = $discounted_price * ($total_after_product_discount > 0 ?
                    $promotion_discount / $total_after_product_discount : 0);
                    $final_price = max(0, $discounted_price - $promotion_discount_per_item);
                    $total_price = $final_price * $item['quantity'];
                    @endphp
                    <tr>
                        <td class="cart_product">
                            <a href="{{ URL('/chi-tiet-san-pham/'.$item['product_id']) }}">
                                <img src="{{ asset($item['product_image']) }}" width="100" alt="">
                            </a>
                        </td>
                        <td class="cart_description">
                            <h4><a
                                    href="{{ URL('/chi-tiet-san-pham/'.$item['product_id']) }}">{{ $item['product_name'] }}</a>
                            </h4>
                            <p>ID: {{ $item['product_id'] }}</p>
                        </td>
                        <td class="cart_price text-right">
                            <p>{{ number_format($item['product_price'], 0, ',', '.') }} VNĐ</p>
                        </td>
                        <td class="cart_price text-right">
                            <p title="Giảm {{ $discount_percentage }}%">
                                {{ $product_discount > 0 ? '-' . number_format($product_discount, 0, ',', '.') . ' VNĐ' : '0 VNĐ' }}
                            </p>
                        </td>
                        <td class="cart_price text-right">
                            <p title="Giá sau mã khuyến mãi {{ $promotion_code }}">
                                {{ number_format($final_price, 0, ',', '.') }} VNĐ
                            </p>
                        </td>
                        <td class="cart_quantity">
                            <form action="{{ URL::to('/update-cart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}" />
                                <input class="cart_quantity_input" type="number" name="quantity"
                                    value="{{ $item['quantity'] }}" min="1">
                                <button style="margin-top: 0;" type="submit" class="btn btn-sm btn-primary">Cập
                                    nhật</button>
                            </form>
                        </td>
                        <td class="cart_total text-right">
                            <p class="cart_total_price">{{ number_format($total_price, 0, ',', '.') }} VNĐ</p>
                        </td>
                        <td class="cart_delete">
                            <a class="cart_quantity_delete" href="{{ URL::to('/remove-cart/'.$item['product_id']) }}">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="8" class="text-right">
                            <h4><b>Tổng tiền gốc: {{ number_format($subtotal, 0, ',', '.') }} VNĐ</b></h4>
                            @if($product_discount_total > 0)
                            <h4 style="color: #28A745;"><b>Giảm giá sản phẩm:
                                    -{{ number_format($product_discount_total, 0, ',', '.') }} VNĐ</b></h4>
                            @endif
                            @if($promotion_discount > 0)
                            <h4 style="color: #28A745;"><b>Giảm giá mã ({{ $promotion_code }}):
                                    -{{ number_format($promotion_discount, 0, ',', '.') }} VNĐ</b></h4>
                            @endif
                            <h4><b>Phí ship: {{ number_format($shipping_fee, 0, ',', '.') }} VNĐ</b></h4>
                            <h4><b>Thành tiền: {{ number_format($total_after, 0, ',', '.') }} VNĐ</b></h4>
                            <a href="{{ URL::to('/clear-cart') }}" class="btn btn-danger">Xóa toàn bộ giỏ hàng</a>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="8" class="text-center">Giỏ hàng trống!</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Form nhập mã khuyến mãi -->
        <div class="promotion-code" style="margin: 20px 0;">
            <h4 style="font-size: 20px;">Áp dụng mã khuyến mãi</h4>
            <form action="{{ URL::to('/apply-promotion') }}" method="POST">
                @csrf
                <div class="form-group">
                    <input type="text" name="promotion_code" class="form-control" placeholder="Nhập mã khuyến mãi"
                        value="{{ $promotion_code }}" style="width: 200px; display: inline-block;">
                    <button type="submit" class="btn btn-warning" style="margin-left: 10px;">Áp dụng</button>
                    @if($promotion_code)
                    <a href="{{ URL::to('/clear-promotion') }}" class="btn btn-warning"
                        style="margin-left: 10px; background-color: red;">Hủy mã</a>
                    @endif
                </div>
                @if(Session::has('promotion_error'))
                <p style="color: #DC3545;">{{ Session::get('promotion_error') }}</p>
                @endif
                @if(Session::has('promotion_success'))
                <p style="color: #28A745;">{{ Session::get('promotion_success') }}</p>
                @endif
            </form>
        </div>

        <!-- Form thanh toán -->
        <h4 style="margin: 40px 0; font-size: 20px;">Chọn hình thức thanh toán</h4>
        <form action="{{ URL::to('/order-place') }}" method="POST" id="payment-form">
            @csrf
            <input type="hidden" name="total_vnpay" value="{{ $total_after }}">
            <input type="hidden" name="promotion_discount" value="{{ $promotion_discount }}">
            <input type="hidden" name="promotion_code" value="{{ $promotion_code }}">
            <input type="hidden" name="product_discount_total" value="{{ $product_discount_total }}">
            <input type="hidden" name="shipping_fee" value="{{ $shipping_fee }}">
            <input type="hidden" name="subtotal" value="{{ $subtotal }}">
            <input type="hidden" name="language" value="vn">
            <div class="payment-options">
                <span>
                    <label><input name="payment_option" value="bằng thẻ" type="radio" required> Trả bằng thẻ</label>
                </span>
                <span>
                    <label><input name="payment_option" value="tiền mặt" type="radio" checked required> Tiền mặt</label>
                </span>
                <span>
                    <label><input name="payment_option" value="VNPay" type="radio" required id="vnpay-option"> Thanh
                        toán VNPay</label>
                </span>
                <div id="vnpay-bank" style="display: none; margin-top: 10px;">
                    <select name="bankCode" class="form-control" style="width: 200px; display: inline-block;">
                        <option value="">Chọn ngân hàng</option>
                        <option value="NCB">Ngân hàng NCB</option>
                        <option value="VNPAYQR">VNPAYQR</option>
                        <option value="VISA">VISA/MASTER</option>
                        <option value="MBBANK">MB Bank</option>
                        <option value="TECHCOMBANK">Techcombank</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success" name="send_order_place">
                    <span id="button-text">Đặt hàng</span>
                    <span id="vnpay-button-text" style="display: none;">
                        <img src="https://vnpay.vn/assets/images/logo.png" alt="VNPay"
                            style="height: 20px; vertical-align: middle; margin-right: 5px;">
                        Thanh toán VNPay
                    </span>
                </button>
            </div>
            @error('payment_option')
            <p style="color: #DC3545;">{{ $message }}</p>
            @enderror
        </form>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const vnpayOption = document.getElementById('vnpay-option');
        const vnpayBank = document.getElementById('vnpay-bank');
        const buttonText = document.getElementById('button-text');
        const vnpayButtonText = document.getElementById('vnpay-button-text');

        function toggleVNPayOptions() {
            if (vnpayOption.checked) {
                vnpayBank.style.display = 'block';
                buttonText.style.display = 'none';
                vnpayButtonText.style.display = 'inline';
            } else {
                vnpayBank.style.display = 'none';
                buttonText.style.display = 'inline';
                vnpayButtonText.style.display = 'none';
            }
        }

        vnpayOption.addEventListener('change', toggleVNPayOptions);
        document.querySelectorAll('input[name="payment_option"]').forEach(option => {
            option.addEventListener('change', toggleVNPayOptions);
        });

        toggleVNPayOptions();

        // Debug: Log giá trị radio button khi submit
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            const selectedOption = document.querySelector('input[name="payment_option"]:checked').value;
            console.log('Selected payment option:', selectedOption);
        });
    });
</script>

<style>
    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
    }

    .btn-primary:hover {
        background-color: #0056B3;
        border-color: #0056B3;
    }

    .btn-warning {
        background-color: #FFC107;
        border-color: #FFC107;
    }

    .btn-warning:hover {
        background-color: #E0A800;
        border-color: #E0A800;
    }

    .btn-success {
        background-color: #28A745;
        border-color: #28A745;
    }

    .btn-success:hover {
        background-color: #218838;
        border-color: #218838;
    }

    .btn-danger {
        background-color: #DC3545;
        border-color: #DC3545;
    }

    .btn-danger:hover {
        background-color: #C82333;
        border-color: #C82333;
    }

    .form-control:focus {
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }

    .cart_menu {
        background-color: #E7F1FF;
        color: #007BFF;
    }

    .cart_info table {
        border: 1px solid #E7F1FF;
    }

    .cart_price,
    .cart_total {
        text-align: right;
    }

    .cart_price p,
    .cart_total_price {
        margin: 0;
    }

    .cart_quantity_input {
        width: 60px;
        display: inline-block;
    }
</style>
@endsection