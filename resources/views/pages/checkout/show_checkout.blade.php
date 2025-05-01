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

        <div class="register-req">
            <p>Điền thông tin giao hàng</p>
        </div>

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
                                <div class="form-group">
                                    <label for="matp">Tỉnh/Thành phố</label>
                                    <select name="matp" id="matp" class="form-control" required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->matp }}">{{ $province->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="maqh">Quận/Huyện</label>
                                    <select name="maqh" id="maqh" class="form-control">
                                        <option value="">Chọn quận/huyện</option>
                                    </select>
                                </div>
                                <input type="text" name="shipping_address" placeholder="Địa chỉ" required>
                                <input type="text" name="shipping_phone" placeholder="Số điện thoại" required>

                                <button type="submit" class="btn btn-primary">Gửi thông tin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection