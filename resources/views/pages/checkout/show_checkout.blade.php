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
            <p>Điền thông tin giao hàng</p>
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
                        <p>Địa chỉ chi tiết</p>
                        <textarea name="message" placeholder="điền thông tin tại đây" rows="16"></textarea>

                    </div>
                </div>
            </div>
        </div>




    </div>
</section>
<!--/#cart_items-->


@endsection