@extends('layout')
@section('content')



@if($search_results->isEmpty())
<p>Không tìm thấy sản phẩm nào.</p>
@else
<div class="features_items">

    <h2 class="title text-center">Kết quả tìm kiếm cho: "{{ $keywords }}"</h2>

    @foreach($search_results as $product)
    <div class="col-sm-4">
        <div class="product-image-wrapper">
            <div class="single-products">
                <div class="productinfo text-center">
                    <a href="{{ url('/chi-tiet-san-pham/'.$product->product_id) }}">
                        <img style=" width: 100%;height: 250px; object-fit: cover;" src="{{$product->product_image }}"
                            alt="">
                        <h2 style="color: green;">Giá: {{ number_format($product->product_price, 0, ',', '.') }} VNĐ
                        </h2>
                        <p>{{ $product->product_name }}</p>
                    </a>
                    <a href="{{ url('/chi-tiet-san-pham/'.$product->product_id) }}"
                        class="btn btn-default add-to-cart">Xem chi tiết</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection