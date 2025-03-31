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



        <div class="register-req">
            <p>Đăng ký để thanh toán đơn hàng</p>
        </div>
        <!--/register-req-->

        <div class="shopper-informations">
            <div class="row">

                <div class="col-sm-5 clearfix">
                    <div class="bill-to">
                        <p>Điền thông tin gửi hàng</p>
                        <div class="form-one">
                            <form action="{{ url('/save-shipping') }}" method="POST">
                                @csrf
                                <input type="text" name="shipping_email" placeholder="Email" required>
                                <input type="text" name="shipping_name" placeholder="Họ và tên" required>
                                <input type="text" name="shipping_address" placeholder="Địa chỉ" required>
                                <input type="text" name="shipping_phone" placeholder="Số điện thoại" required>
                                <button type="submit" class="btn btn-primary">Gửi thông tin</button>
                            </form>
                        </div>

                    </div>
                </div>

                <div class="col-sm-4">
                    <div class="order-message">
                        <p>Shipping Order</p>
                        <textarea name="message" placeholder="Notes about your order, Special Notes for Delivery"
                            rows="16"></textarea>
                        <label><input type="checkbox"> Shipping to bill address</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="review-payment">
            <h2>Xem lại giỏ hàng</h2>
        </div>


        <div class="payment-options">
            <span>
                <label><input type="checkbox"> Direct Bank Transfer</label>
            </span>
            <span>
                <label><input type="checkbox"> Check Payment</label>
            </span>
            <span>
                <label><input type="checkbox"> Paypal</label>
            </span>
        </div>
    </div>
</section>
<!--/#cart_items-->


@endsection