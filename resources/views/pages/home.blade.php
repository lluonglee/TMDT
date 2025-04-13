@extends('layout')

@section('content')
<div class="features_items">
    <!--features_items-->
    <h2 class="title text-center" style="color: #d9534f;">Tất cả sản phẩm</h2>
    @foreach($all_product as $product)
    @php
    $discount_price = $product->product_price * (1 - $product->discount / 100);
    @endphp

    <a href="{{ URL('/chi-tiet-san-pham/'.$product->product_id) }}">
        <div class="col-sm-4">
            <!-- col-6 col-md-3 mb-4 4 sp-->

            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <img style=" width: 100%;height: 250px; object-fit: cover;"
                            src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" />

                        @php
                        $discount_price = $product->product_price * (1 - $product->discount / 100);
                        @endphp

                        @if($product->discount > 0)
                        <h2>
                            <span style="text-decoration: line-through; color: red;">
                                Giá: {{ number_format($product->product_price, 0, ',', '.') }} VNĐ
                            </span>
                            <br>
                            <span style="color: green; font-weight: bold;">
                                Còn: {{ number_format($discount_price, 0, ',', '.') }} VNĐ
                            </span>
                        </h2>
                        @else
                        <h2>{{ number_format($product->product_price, 0, ',', '.') }} VNĐ</h2>
                        @endif

                        <p>{{ $product->product_name }}</p>

                        <!-- Form để thêm vào giỏ hàng -->
                        <form action="{{ URL('/save-cart') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id_hidden" value="{{ $product->product_id }}">

                            <!-- Ẩn input số lượng nhưng vẫn giữ giá trị mặc định là 1 -->
                            <input type="hidden" name="qty" value="1">

                            <button type="submit" class="btn btn-default add-to-cart">
                                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                            </button>
                        </form>
                    </div>
                </div>
                <div class="choose">
                    <ul class="nav nav-pills nav-justified">
                        <li><a href="#"><i class="fa fa-plus-square"></i> Yêu thích</a></li>
                        <li><a href="#"><i class="fa fa-plus-square"></i> Thêm so sánh</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </a>

    @endforeach



</div>
<!--features_items-->


@endsection