@extends('layout')
@section('content')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{ URL('/') }}">Trang Chủ</a></li>
                <li><a href="{{ URL('/show-cart') }}">Giỏ hàng</a></li>
                <li class="active">Chỉnh sửa thông tin giao hàng</li>
            </ol>
        </div>

        <div class="register-req">
            <p>Chỉnh sửa thông tin giao hàng</p>
        </div>

        <div class="shopper-informations">
            <div class="row">
                <div class="col-sm-5 clearfix">
                    <div class="bill-to">
                        <p>Thông tin gửi hàng</p>
                        <div class="form-one">
                            <form action="{{ url('/update-shipping') }}" method="POST">
                                @csrf
                                <input type="hidden" name="shipping_id" value="{{ $shipping->shipping_id }}">
                                <input type="text" name="shipping_email" value="{{ $shipping->shipping_email }}"
                                    placeholder="Email" required>
                                <input type="text" name="shipping_name" value="{{ $shipping->shipping_name }}"
                                    placeholder="Họ và tên" required>
                                <div class="form-group">
                                    <label for="matp">Tỉnh/Thành phố</label>
                                    <select name="matp" id="matp" class="form-control" required>
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        @foreach($provinces as $province)
                                        <option value="{{ $province->matp }}"
                                            {{ $province->matp == $shipping->matp ? 'selected' : '' }}>
                                            {{ $province->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="maqh">Quận/Huyện</label>
                                    <select name="maqh" id="maqh" class="form-control">
                                        <option value="">Chọn quận/huyện</option>
                                        @if($shipping->maqh)
                                        <option value="{{ $shipping->maqh }}" selected>
                                            {{ DB::table('tbl_quanhuyen')->where('maqh', $shipping->maqh)->value('name') }}
                                        </option>
                                        @endif
                                    </select>
                                </div>
                                <input type="text" name="shipping_address" value="{{ $shipping->shipping_address }}"
                                    placeholder="Địa chỉ" required>
                                <input type="text" name="shipping_phone" value="{{ $shipping->shipping_phone }}"
                                    placeholder="Số điện thoại" required>

                                <button type="submit" class="btn btn-primary">Cập nhật thông tin</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection