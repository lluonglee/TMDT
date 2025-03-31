@extends('layout')

@section('content')
<div class="features_items">
    <!--features_items-->
    <h2 class="title text-center">Danh mục sản phẩm</h2>

    @foreach($all_product as $product)
    <a href="{{URL('/chi-tiet-san-pham/'.$product->product_id)}}">
        <div class="col-sm-4">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <img src="{{ asset($product->product_image) }}" alt="" />
                        <h2>{{ number_format($product->product_price, 0, ',', '.') }} VNĐ</h2>
                        <p>{{$product->product_name}}</p>
                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm giỏ
                            hàng</a>
                    </div>
                    <!-- <div class="product-overlay">
                    <div class="overlay-content">
                        <h2>$56</h2>
                        <p>Easy Polo Black Edition</p>
                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                            cart</a>
                    </div>
                </div> -->
                </div>
                <div class="choose">
                    <ul class="nav nav-pills nav-justified">
                        <li><a href="#"><i class="fa fa-plus-square"></i>yêu thích</a></li>
                        <li><a href="#"><i class="fa fa-plus-square"></i>Thêm so sánh</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </a>

    @endforeach


</div>
<!--features_items-->

<!-- <div class="category-tab">
  
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#tshirt" data-toggle="tab">T-Shirt</a></li>
            <li><a href="#blazers" data-toggle="tab">Blazers</a></li>
            <li><a href="#sunglass" data-toggle="tab">Sunglass</a></li>
            <li><a href="#kids" data-toggle="tab">Kids</a></li>
            <li><a href="#poloshirt" data-toggle="tab">Polo shirt</a></li>
        </ul>
    </div>
    <div class="tab-content">
        <div class="tab-pane fade active in" id="tshirt">
            <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{('public/frontend/frontend/gallery1.jpg')}}" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                                cart</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="tab-pane fade" id="blazers">
            <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{('public/frontend/frontend/gallery4.jpg')}}" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                                cart</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="tab-pane fade" id="sunglass">
            <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{('public/frontend/')}}gallery3.jpg" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                                cart</a>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{('public/frontend/')}}gallery4.jpg" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                                cart</a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="tab-pane fade" id="kids">
            <div class="col-sm-3">
                <div class="product-image-wrapper">
                    <div class="single-products">
                        <div class="productinfo text-center">
                            <img src="{{('public/frontend/images/gallery1.jpg')}}" alt="" />
                            <h2>$56</h2>
                            <p>Easy Polo Black Edition</p>
                            <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                                cart</a>
                        </div>

                    </div>
                </div>
            </div>

            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <img src="{{('public/frontend/images/gallery2.jpg')}}x" alt="" />
                        <h2>$56</h2>
                        <p>Easy Polo Black Edition</p>
                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                            cart</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="poloshirt">
        <div class="col-sm-3">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <img src="{{('public/frontend/images/gallery2.jpg')}}" alt="" />
                        <h2>$56</h2>
                        <p>Easy Polo Black Edition</p>
                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                            cart</a>
                    </div>

                </div>
            </div>
        </div>

        <div class="product-image-wrapper">
            <div class="single-products">
                <div class="productinfo text-center">
                    <img src="{{('public/frontend/images/gallery1.jpg')}}" alt="" />
                    <h2>$56</h2>
                    <p>Easy Polo Black Edition</p>
                    <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add to
                        cart</a>
                </div>

            </div>
        </div>
    </div>
</div> -->
</div>
</div>
<!--/category-tab-->

<!--recommended_items-->
<!-- <div class="recommended_items">
   
    <h2 class="title text-center">recommended items</h2>

    <div id="recommended-item-carousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="item active">
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <img src="{{('public/frontend/images/recommend1.jpg')}}" alt="" />
                                <h2>$56</h2>
                                <p>Easy Polo Black Edition</p>
                                <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add
                                    to cart</a>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <img src="{{('public/frontend/images/recommend2.jpg')}}" alt="" />
                                <h2>$56</h2>
                                <p>Easy Polo Black Edition</p>
                                <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add
                                    to cart</a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="item">
                <div class="col-sm-4">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <img src="{{('public/frontend/images/recommend1.jpg')}}" alt="" />
                                <h2>$56</h2>
                                <p>Easy Polo Black Edition</p>
                                <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Add
                                    to cart</a>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <a class="left recommended-item-control" href="#recommended-item-carousel" data-slide="prev">
            <i class="fa fa-angle-left"></i>
        </a>
        <a class="right recommended-item-control" href="#recommended-item-carousel" data-slide="next">
            <i class="fa fa-angle-right"></i>
        </a>
    </div>
</div> -->
<!--/recommended_items-->
@endsection