@extends('layout')

@section('content')
<div class="features_items">
    <h2 class="title text-center">Thương Hiệu:{{ $brand_name }}</h2>
    @foreach($brand_by_id as $product)
    <a href="{{URL('/chi-tiet-san-pham/'.$product->product_id)}}">
        <div class="col-sm-4">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" />
                        <h2>{{ number_format($product->product_price, 0, ',', '.') }} VNĐ</h2>
                        <p>{{ $product->product_name }}</p>
                        <a href="#" class="btn btn-default add-to-cart">
                            <i class="fa fa-shopping-cart"></i>Thêm giỏ hàng
                        </a>
                    </div>
                </div>
                <div class="choose">
                    <ul class="nav nav-pills nav-justified">
                        <li><a href="#"><i class="fa fa-plus-square"></i>Yêu thích</a></li>
                        <li><a href="#"><i class="fa fa-plus-square"></i>Thêm so sánh</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection