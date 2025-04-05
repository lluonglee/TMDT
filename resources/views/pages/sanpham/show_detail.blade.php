@extends('layout')
@section('content')

<div class="product-details">
    <div class="col-sm-5">
        <div class="view-product">
            <img src="{{ asset( $detail_product->product_image) }}" alt="{{  $detail_product->product_name }}" />
            <h3>ZOOM</h3>
        </div>
    </div>
    <div class="col-sm-7">
        <div class="product-information">
            <h2>{{ $detail_product->product_name }}</h2>
            <p>Mã sản phẩm: {{ $detail_product->product_id }}</p>

            <form action="{{ URL::to('/save-cart') }}" method="POST">
                @csrf
                <span>
                    @php
                    $discount_price = $detail_product->product_price * (1 - $detail_product->discount / 100);
                    @endphp

                    @if($detail_product->discount > 0)
                    <span>
                        <span style="text-decoration: line-through; color: red; font-size: 18px;">
                            {{ number_format($detail_product->product_price, 0, ',', '.') }} VNĐ
                        </span>
                        <br>
                        <span style="color: green; font-size: 24px; font-weight: bold;">
                            {{ number_format($discount_price, 0, ',', '.') }} VNĐ
                        </span>
                    </span>
                    @else
                    <span style="font-size: 24px; font-weight: bold;">
                        {{ number_format($detail_product->product_price, 0, ',', '.') }} VNĐ
                    </span>
                    @endif

                    <label>Số lượng:</label>
                    <input type="number" name="qty" value="1" min="1" />
                    <input type="hidden" name="product_id_hidden" value="{{ $detail_product->product_id }}" />
                    <button type="submit" class="btn btn-default cart">
                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </span>
            </form>

            <p><b>Danh mục:</b> {{ $detail_product->category_name }}</p>
            <p><b>Thương hiệu:</b> {{ $detail_product->brand_name }}</p>
            <p><b>Mô tả:</b> {{ $detail_product->product_desc }}</p>
        </div>
    </div>
</div>


<div class="category-tab shop-details-tab">
    <!--category-tab-->
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#details" data-toggle="tab">Chi tiết sản phẩm</a></li>
            <li><a href="#companyprofile" data-toggle="tab">Hồ sơ công ty</a></li>
            <li><a href="#reviews" data-toggle="tab">đánh giá</a></li>
        </ul>
    </div>
    <div class="tab-content  active">
        <div class="tab-pane fade" id="details">

            <p>{{ $detail_product->product_desc }}</p>
        </div>

        <div class="tab-pane fade" id="companyprofile">
            <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="images/home/gallery1.jpg" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <button type="button" class="btn btn-default add-to-cart"><i
                                    class="fa fa-shopping-cart"></i>Add to cart</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <div class="tab-pane fade in" id="reviews">
            <div class="col-sm-12">
                <ul>
                    <li><a href=""><i class="fa fa-user"></i>EUGEN</a></li>
                    <li><a href=""><i class="fa fa-clock-o"></i>12:41 PM</a></li>
                    <li><a href=""><i class="fa fa-calendar-o"></i>31 DEC 2014</a></li>
                </ul>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                    et dolore magna aliqua.Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut
                    aliquip ex ea commodo consequat.Duis aute irure dolor in reprehenderit in voluptate velit esse
                    cillum dolore eu fugiat nulla pariatur.</p>
                <p><b>Write Your Review</b></p>

                <form action="#">
                    <span>
                        <input type="text" placeholder="Your Name" />
                        <input type="email" placeholder="Email Address" />
                    </span>
                    <textarea name=""></textarea>
                    <b>Rating: </b> <img src="images/product-details/rating.png" alt="" />
                    <button type="button" class="btn btn-default pull-right">
                        Submit
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
<!--/category-tab-->
<div class="recommended_items">
    <h2 class="title text-center">Gợi ý sản phẩm</h2>

    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            @php $count = 0; @endphp
            @foreach($related_products->chunk(3) as $chunk)
            <div class="item {{ $count == 0 ? 'active' : '' }}">
                @foreach($chunk as $product)
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <img src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" />
                                <h2>{{ number_format($product->product_price, 0, ',', '.') }} VNĐ</h2>
                                <p>{{ $product->product_name }}</p>
                                <a href="{{ URL('/chi-tiet-san-pham/'.$product->product_id) }}"
                                    class="btn btn-default add-to-cart">
                                    <i class="fa fa-shopping-cart"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @php $count++; @endphp
            @endforeach
        </div>
        <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
            <i class="fa fa-angle-left"></i>
        </a>
        <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div>
<!--/recommended_items-->
@endsection