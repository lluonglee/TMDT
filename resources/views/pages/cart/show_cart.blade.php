@extends('layout')
@section('content')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{ URL('/') }}">Trang Chủ</a></li>
                <li class="active">Giỏ hàng của bạn</li>
            </ol>
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
                    @php
                    $subtotal = 0;
                    @endphp
                    @foreach(Session::get('cart') as $item)
                    @php
                    $total_price = $item['product_price'] * $item['quantity'];
                    $subtotal += $total_price;
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
                        <td class="cart_quantity">
                            <form action="{{ URL::to('/update-cart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}" />
                                <input class="cart_quantity_input" type="number" name="quantity"
                                    value="{{ $item['quantity'] }}" min="1" max="10">
                                <button style="margin-top: 0;" type="submit" class="btn btn-sm btn-primary">Cập
                                    nhật</button>
                            </form>
                        </td>
                        <td class="cart_total">
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
                        <td colspan="6" class="text-right">
                            <h4><b>Tổng tiền: {{ number_format($subtotal, 0, ',', '.') }} VNĐ</b></h4>
                            <a href="{{ URL::to('/clear-cart') }}" class="btn btn-danger">Xóa toàn bộ giỏ hàng</a>
                        </td>
                    </tr>
                    @else
                    <tr>
                        <td colspan="6" class="text-center">Giỏ hàng trống!</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>

<section id="do_action">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <div class="total_area">
                    <ul>
                        <li>Tổng <span>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span></li>
                        <li>Phí vận chuyển
                            <span>{{ $shipping_fee > 0 ? number_format($shipping_fee, 0, ',', '.') . ' VNĐ' : (Session::has('shipping_id') ? 'Miễn phí' : 'Chưa chọn địa chỉ') }}</span>
                        </li>
                        <li>Thành tiền <span>{{ number_format($subtotal + $shipping_fee, 0, ',', '.') }} VNĐ</span></li>
                    </ul>
                    @if(Session::has('cart') && count(Session::get('cart')) > 0)
                    @if(Session::has('customer_id'))
                    @if(Session::has('shipping_id'))
                    <li>
                        <a class="btn btn-success" href="{{ URL('/payment') }}"><i class="fa fa-money"></i> Thanh
                            toán</a>
                        <a class="btn btn-warning" href="{{ URL('/edit-shipping') }}"><i class="fa fa-edit"></i> Chỉnh
                            sửa thông tin</a>
                    </li>
                    @else
                    <li><a class="btn btn-primary" href="{{ URL('/checkout') }}"><i class="fa fa-lock"></i> Điền thông
                            tin giao hàng</a></li>
                    @endif
                    @else
                    <li><a class="btn btn-primary" href="{{ URL('/customer/login') }}"><i class="fa fa-lock"></i> Đăng
                            nhập để thanh toán</a></li>
                    @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection