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
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Hình ảnh</td>
                        <td class="description">Mô tả</td>
                        <td class="price">Giá gốc</td>
                        <td class="price">Giá sau mã khuyến mãi</td>
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
                    // Tính tổng tiền sau giảm giá sản phẩm
                    foreach (Session::get('cart') as $item) {
                    $discount_percentage = $item['product_discount'] ?? 0;
                    $discounted_price = $item['product_price'] * (1 - $discount_percentage / 100);
                    $subtotal += $item['product_price'] * $item['quantity'];
                    $product_discount_total += ($item['product_price'] - $discounted_price) * $item['quantity'];
                    }
                    // Giới hạn promotion_discount
                    $total_after_product_discount = $subtotal - $product_discount_total;
                    $promotion_discount = min($promotion_discount, $total_after_product_discount);
                    $discount_ratio = $total_after_product_discount > 0 ? $promotion_discount /
                    $total_after_product_discount : 0;
                    @endphp
                    @foreach(Session::get('cart') as $item)
                    @php
                    $discount_percentage = $item['product_discount'] ?? 0;
                    $discounted_price = $item['product_price'] * (1 - $discount_percentage / 100);
                    $promotion_discount_per_item = $discounted_price * $discount_ratio;
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
                        <td class="cart_price">
                            <p>{{ number_format($item['product_price'], 0, ',', '.') }} VNĐ</p>
                        </td>

                        <td class="cart_price">
                            @if($promotion_discount > 0)
                            <p>{{ number_format($final_price, 0, ',', '.') }} VNĐ</p>
                            @else
                            <p>{{ number_format($discounted_price, 0, ',', '.') }} VNĐ</p>
                            @endif
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
                        <td class="cart_total">
                            <!-- <p class="cart_total_price">{{ number_format($total_price, 0, ',', '.') }} VNĐ</p> -->
                            <p class="cart_total_price">{{ number_format($item['product_price'], 0, ',', '.') }} VNĐ</p>
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
                            <h4><b>Thành tiền:
                                    {{ number_format(max(0, $subtotal - $product_discount_total - $promotion_discount), 0, ',', '.') }}
                                    VNĐ</b></h4>
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
                    <button type="submit" class="btn btn-warning" style="margin-left: 10px;   ">Áp dụng</button>
                    @if($promotion_code)
                    <a href="{{ URL::to('/clear-promotion') }}" class="btn btn-warning"
                        style="margin-left: 10px; background-color: red;">Hủy
                        mã</a>
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

        <h4 style="margin: 40px 0; font-size: 20px;">Chọn hình thức thanh toán</h4>
        <form action="{{ URL::to('/order-place') }}" method="POST">
            @csrf
            <input type="hidden" name="promotion_discount" value="{{ $promotion_discount }}">
            <input type="hidden" name="promotion_code" value="{{ $promotion_code }}">
            <input type="hidden" name="product_discount_total" value="{{ $product_discount_total }}">
            <div class="payment-options">
                <span>
                    <label><input name="payment_option" value="bằng thẻ" type="radio" required> Trả bằng thẻ</label>
                </span>
                <span>
                    <label><input name="payment_option" value="tiền mặt" type="radio" required> Tiền mặt</label>
                </span>
                <input type="submit" value="Đặt hàng" name="send_order_place" class="btn btn-success">
            </div>
        </form>
    </div>
</section>

<style>
    .btn-primary {
        background-color: #007BFF;
        border-color: #007BFF;
    }

    .btn-primary:hover {
        background-color: #0056B3;
        border-color: #0056B3;
    }

    button .btn-warning {
        background-color: #007BFF;
        border-color: #007BFF;

    }

    button .btn-warning:hover {
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

    del {
        color: #999;
    }

    .cart_menu {
        background-color: #E7F1FF;
        color: #007BFF;
    }

    .cart_info table {
        border: 1px solid #E7F1FF;
    }
</style>
@endsection