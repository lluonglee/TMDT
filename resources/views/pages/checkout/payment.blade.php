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
        <!--/breadcrums-->




        <!--/register-req-->


        <div class="review-payment">
            <h2>Xem lại giỏ hàng</h2>
        </div>
        <div class="table-responsive cart_info">
            <table class="table table-condensed">
                <thead>
                    <tr class="cart_menu">
                        <td class="image">Hình ảnh</td>
                        <td class="description">Mô tả</td>
                        <td class="price">Giá</td>
                        <td class="quantity">Số lượng</td>
                        <td class="total">Tổng tiền</td>
                        <td></td>
                    </tr>
                </thead>
                <tbody>
                    @if(Session::has('cart') && count(Session::get('cart')) > 0)
                    @foreach(Session::get('cart') as $item)
                    <tr>
                        <td class="cart_product">
                            <a href=""><img src="{{ $item['product_image'] }}" width="100" alt=""></a>
                        </td>
                        <td class="cart_description">
                            <h4><a href="">{{ $item['product_name'] }}</a></h4>
                            <p>ID: {{ $item['product_id'] }}</p>
                        </td>
                        <td class="cart_price">
                            <p>{{ number_format($item['product_price'], 0, ',', '.') }} VNĐ</p>
                        </td>
                        <td class="cart_quantity">
                            <div class="cart_quantity_button">
                                <form action="{{ URL::to('/update-cart') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item['product_id'] }}" />
                                    <input class="cart_quantity_input" type="number" name="quantity"
                                        value="{{ $item['quantity'] }}" min="1">
                                    <button type="submit" class="btn btn-sm btn-primary">Cập nhật</button>
                                </form>
                            </div>
                        </td>
                        <td class="cart_total">
                            <p class="cart_total_price">
                                {{ number_format($item['product_price'] * $item['quantity'], 0, ',', '.') }} VNĐ
                            </p>
                        </td>
                        <td class="cart_delete">
                            <a class="cart_quantity_delete" href="{{ URL::to('/remove-cart/'.$item['product_id']) }}">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="6" class="text-center">Giỏ hàng trống!</td>
                    </tr>
                    @endif
                </tbody>

            </table>
        </div>
        <h4 style="margin: 40px 0; font-size: 20px;">Chọn hình thức thanh toán</h4>

        <form action="{{ URL('/order-place') }}" method="post">
            @csrf
            <div class="payment-options">
                <span>
                    <label><input name="payment_option" value="bằng thẻ" type="radio" required> Trả bằng thẻ</label>
                </span>
                <span>
                    <label><input name="payment_option" value="tiền mặt" type="radio" required> Tiền mặt</label>
                </span>
                <input type="submit" value="Đặt hàng" name="send_order_place">
            </div>
        </form>


    </div>
</section>
<!--/#cart_items-->


@endsection