@extends('layout')
@section('content')

<div class="product-details">
    <div class="col-sm-5">
        <div class="view-product">
            <img src="{{ asset( $detail_product->product_image) }}" alt="{{  $detail_product->product_name }}" />
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
                    <span style="font-size: 24px; font-weight: bold; color: green;">
                        {{ number_format($detail_product->product_price, 0, ',', '.') }} VNĐ
                    </span>
                    @endif

                    <label>Số lượng:</label>
                    <input type="number" name="qty" value="1" min="1" max="10" />
                    <input type="hidden" name="product_id_hidden" value="{{ $detail_product->product_id }}" />
                    <button type="submit" class="btn btn-default cart">
                        <i class="fa fa-shopping-cart"></i> Mua ngay
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
        <ul style="background-color: #A52A2A;" class="nav nav-tabs">
            <li class="active"><a href="#details" data-toggle="tab">Chi tiết sản phẩm</a></li>

            <li><a href="#reviews" data-toggle="tab">đánh giá</a></li>
        </ul>
    </div>
    <div class="tab-content  active">
        <div class="tab-pane fade" id="details">

            <p>{{ $detail_product->product_desc }}</p>
        </div>


        <!-- <div class="tab-pane fade in" id="reviews">
            @if(Session::has('customer_id'))
            @php
            $customer_id = Session::get('customer_id');
            $hasPurchased = DB::table('tbl_order')
            ->join('tbl_order_detail', 'tbl_order.order_id', '=', 'tbl_order_detail.order_id')
            ->where('tbl_order.customer_id', $customer_id)
            ->where('tbl_order_detail.product_id', $detail_product->product_id)
            ->whereIn('tbl_order.order_status', ['Đã giao hàng', 'Hoàn thành'])
            ->exists();
            $hasReviewed = DB::table('tbl_product_reviews')
            ->where('customer_id', $customer_id)
            ->where('product_id', $detail_product->product_id)
            ->exists();
            @endphp

            @if($hasPurchased && !$hasReviewed)
            <form action="{{ route('review.store', ['product_id' => $detail_product->product_id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="rating">Số sao:</label><br>
                    @for ($i = 1; $i <= 5; $i++) <label>
                        <input type="radio" name="rating" value="{{ $i }}" required> {{ $i }} ⭐
                        </label>
                        @endfor
                        @error('rating')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror
                </div>

                <div class="form-group">
                    <label for="comment">Nhận xét:</label>
                    <textarea name="comment" id="comment" class="form-control" rows="4"
                        placeholder="Viết nhận xét của bạn..."></textarea>
                    @error('comment')
                    <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
            </form>
            @elseif($hasReviewed)
            <p>Bạn đã đánh giá sản phẩm này.</p>
            @else
            <p>Bạn cần mua và nhận sản phẩm này để có thể đánh giá.</p>
            @endif
            @else
            <p>Vui lòng <a href="{{ url('/customer/login') }}">đăng nhập</a> để đánh giá sản phẩm.</p>
            @endif

            <hr>


            <h4>Đánh giá sản phẩm:</h4>
            @if ($tbl_product_reviews->isEmpty())
            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
            @else
            @foreach ($tbl_product_reviews as $review)
            <div class="review mt-3 mb-3 p-2 border rounded">
                <strong>{{ $review->customer_name }}</strong> -
                <span>
                    @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                        ⭐
                        @else
                        ☆
                        @endif
                        @endfor
                </span>
                <p>{{ $review->comment }}</p>
                <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') }}</small>
            </div>
            @endforeach
            @endif
        </div> -->
        <div class="tab-pane fade in" id="reviews">
            @if(Session::has('customer_id'))
            @php
            $customer_id = Session::get('customer_id');
            $hasPurchased = DB::table('tbl_order')
            ->join('tbl_order_detail', 'tbl_order.order_id', '=', 'tbl_order_detail.order_id')
            ->where('tbl_order.customer_id', $customer_id)
            ->where('tbl_order_detail.product_id', $detail_product->product_id)
            ->whereIn('tbl_order.order_status', ['Đã giao hàng', 'Hoàn thành'])
            ->exists();
            $hasReviewed = DB::table('tbl_product_reviews')
            ->where('customer_id', $customer_id)
            ->where('product_id', $detail_product->product_id)
            ->exists();
            @endphp

            @if($hasPurchased && !$hasReviewed)
            <form action="{{ route('review.store', ['product_id' => $detail_product->product_id]) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="rating">Số sao:</label><br>
                    @for ($i = 1; $i <= 5; $i++) <label>
                        <input type="radio" name="rating" value="{{ $i }}" required> {{ $i }} ⭐
                        </label>
                        @endfor
                        @error('rating')
                        <span style="color: red;">{{ $message }}</span>
                        @enderror
                </div>

                <div class="form-group">
                    <label for="comment">Nhận xét:</label>
                    <textarea name="comment" id="comment" class="form-control" rows="4"
                        placeholder="Viết nhận xét của bạn..."></textarea>
                    @error('comment')
                    <span style="color: red;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
            </form>
            @elseif($hasReviewed)
            <p>Bạn đã gửi đánh giá cho sản phẩm này. </p>
            @else
            <p>Bạn cần mua và nhận sản phẩm này để có thể đánh giá.</p>
            @endif
            @else
            <p>Vui lòng <a href="{{ url('/customer/login') }}">đăng nhập</a> để đánh giá sản phẩm.</p>
            @endif

            <hr>

            <!-- Danh sách các đánh giá -->
            <h4>Đánh giá sản phẩm:</h4>
            @if ($tbl_product_reviews->where('status', 'approved')->isEmpty())
            <p>Chưa có đánh giá nào cho sản phẩm này.</p>
            @else
            @foreach ($tbl_product_reviews->where('status', 'approved') as $review)
            <div class="review mt-3 mb-3 p-2 border rounded">
                <strong>{{ $review->customer_name }}</strong> -
                <span>
                    @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                        ⭐
                        @else
                        ☆
                        @endif
                        @endfor
                </span>
                <p>{{ $review->comment }}</p>
                <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->format('d/m/Y H:i') }}</small>
            </div>
            @endforeach
            @endif
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
                                <img style=" width: 100%;height: 250px; object-fit: cover;"
                                    src="{{ asset($product->product_image) }}" alt="{{ $product->product_name }}" />
                                <h2 style="color: green;">Giá: {{ number_format($product->product_price, 0, ',', '.') }}
                                    VNĐ</h2>
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