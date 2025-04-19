<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Home | E-Shopper</title>
    <link href="{{asset('public/frontend/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('public/frontend/css/font-awesome.min.css')}} " rel="stylesheet">
    <link href="{{asset('public/frontend/css/prettyPhoto.css')}} " rel="stylesheet">
    <link href="{{asset('public/frontend/css/price-range.css')}} " rel="stylesheet">
    <link href="{{asset('public/frontend/css/animate.css')}} " rel="stylesheet">
    <link href="{{asset('public/frontend/css/main.css')}} " rel="stylesheet">
    <link href="{{asset('public/frontend/css/responsive.css')}} " rel="stylesheet">

    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="images/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/ico/apple-touch-icon-72-precomposed.png">

    <link rel="apple-touch-icon-precomposed" href="images/ico/apple-touch-icon-57-precomposed.png">
    <style>
    /* Phần input tìm kiếm */
    /* Phần input tìm kiếm */
    .col-sm-3 form input[type="text"] {
        width: 100%;
        padding: 10px;
        border: 2px solid #fff;
        /* Màu đỏ */
        border-radius: 5px;
        font-size: 16px;
        color: #333;
        background-color: #fff;
        /* Nền trắng */
        outline: none;
        transition: border-color 0.3s ease-in-out;
    }

    .col-sm-3 form input[type="text"]:focus {
        border-color: #c9302c;
        /* Đổi màu viền khi focus (đỏ đậm hơn) */
    }

    /* Phần nút tìm kiếm */
    .col-sm-3 form button {
        padding: 10px 15px;
        margin-left: 10px;
        background-color: #d9534f;
        /* Màu đỏ */
        border: none;
        border-radius: 5px;
        color: white;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        /* Căn chỉnh icon và văn bản nằm ngang */
        align-items: center;
        /* Căn giữa icon và văn bản theo chiều dọc */
    }

    .col-sm-3 form button:hover {
        background-color: #c9302c;
        /* Đổi màu khi hover (đỏ đậm hơn) */
    }

    .form-flex {
        display: flex;
    }



    /* Icon tìm kiếm */
    .col-sm-3 form button i {
        font-size: 18px;
        margin-right: 5px;
        /* Tạo khoảng cách giữa icon và văn bản */
    }
    </style>
</head>
<!--/head-->

<body>

    <header id="header">
        <!--header-->
        <!--header_top-->
        <!-- <div class="header_top">
          
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="contactinfo">

                            <ul class="nav nav-pills">
                                <li><a href="#"><i class="fa fa-phone"></i> +2 95 01 88 821</a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i> info@domain.com</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="social-icons pull-right">
                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="#"><i class="fa fa-dribbble"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!--/header_top-->

        <div class="header-middle">
            <!--header-middle-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="logo pull-left">
                            <a href="{{URL('/')}}"><img style="width: 100px; height: 50px; object-fit: cover;"
                                    src="{{('public/frontend/images/logo3.png')}}" alt="" /></a>
                        </div>
                        <div class="btn-group pull-right">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                    data-toggle="dropdown">
                                    USA
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Canada</a></li>
                                    <li><a href="#">UK</a></li>
                                </ul>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-default dropdown-toggle usa"
                                    data-toggle="dropdown">
                                    DOLLAR
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a href="#">Canadian Dollar</a></li>
                                    <li><a href="#">Pound</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <div class="shop-menu pull-right">

                            <ul class="nav navbar-nav">
                                <li><a href="#"><i class="fa fa-star"></i> Danh sách yêu thích</a></li>

                                @if(Session::has('customer_id'))
                                @if(Session::has('shipping_id'))
                                <li><a href="{{ URL('/payment') }}"><i class="fa fa-money"></i> Thanh toán</a></li>
                                @else
                                <li><a href="{{ URL('/checkout') }}"><i class="fa fa-lock"></i> Điền thông tin giao
                                        hàng</a></li>
                                @endif
                                @else
                                <li style="display: none;">
                                    <a href="{{ URL('/login-checkout') }}"><i class="fa fa-lock"></i> Đăng nhập để thanh
                                        toán</a>
                                </li>
                                @endif

                                <li><a href="{{ URL('/show-cart') }}"><i class="fa fa-shopping-cart"></i> Giỏ hàng</a>
                                </li>

                                @if(Session::has('customer_id'))
                                <li><a href="{{ URL('/order-history') }}"><i class="fa fa-history"></i> Lịch sử đơn
                                        hàng</a></li>
                                <li><a href="{{ URL('/customer/logout') }}"><i class="fa fa-sign-out"></i> Đăng xuất</a>
                                </li>
                                @else
                                <li><a href="{{ URL('/customer/login') }}"><i class="fa fa-lock"></i> Đăng nhập</a></li>
                                @endif
                            </ul>

                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--/header-middle-->

        <div class="header-bottom">
            <!--header-bottom-->
            <div class="container">
                <div class="row">
                    <div class="col-sm-9">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle" data-toggle="collapse"
                                data-target=".navbar-collapse">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>
                        <!-- <div class="mainmenu pull-left">
                            <ul class="nav navbar-nav collapse navbar-collapse">
                                <li><a href="{{ url('/trang-chu') }}" class="active">Trang Chủ</a></li>
                                <li class="dropdown"><a href="#">Sản phẩm<i class="fa fa-angle-down"></i></a>
                                    <ul role="menu" class="sub-menu">
                                        <li><a href="shop.html">Products</a></li>

                                    </ul>
                                </li>
                                <li class="dropdown"><a href="#">Tin tức<i class="fa fa-angle-down"></i></a>

                                </li>
                                <li><a href="404.html">Giỏ hàng</a></li>
                                <li><a href="contact-us.html">Liên hệ</a></li>
                            </ul>
                        </div> -->
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ url('/tim-kiem') }}" method="POST">
                            @csrf
                            <div class="form-flex">
                                <input type="text" name="keywords" placeholder="Tìm kiếm sản phẩm..." required>
                                <button type="submit"><i class="fa fa-search"></i></button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
        <!--/header-bottom-->
    </header>
    <!--/header-->

    <section id="slider">
        <!--slider-->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div id="slider-carousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#slider-carousel" data-slide-to="0" class="active"></li>
                            <li data-target="#slider-carousel" data-slide-to="1"></li>
                            <li data-target="#slider-carousel" data-slide-to="2"></li>
                        </ol>

                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="col-sm-6">
                                    <h1><span style="color: #AFEEEE;">LOCAL</span>-BRAND</h1>
                                    <h2>Shop Authentic Local Fashion</h2>
                                    <p>Khám phá các sản phẩm thời trang độc đáo từ các thương hiệu nội địa. Chúng tôi
                                        mang đến những thiết kế sáng tạo, chất lượng cao, hoàn hảo cho phong cách sống
                                        hiện đại. Hãy tạo nên dấu ấn riêng với những món đồ đặc biệt từ Local Brand.</p>
                                    <button type="button" style="background-color: #d9534f;"
                                        class="btn btn-default get">Mua ngay</button>
                                </div>

                                <div class="col-sm-6">
                                    <img src="{{('public/frontend/images/slide4.webp')}}" class="girl img-responsive"
                                        alt="" />

                                </div>
                            </div>
                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span style="color: #AFEEEE;">LOCAL</span>-BRAND</h1>
                                    <h2>Shop Authentic Local Fashion</h2>
                                    <p>Khám phá các sản phẩm thời trang độc đáo từ các thương hiệu nội địa. Chúng tôi
                                        mang đến những thiết kế sáng tạo, chất lượng cao, hoàn hảo cho phong cách sống
                                        hiện đại. Hãy tạo nên dấu ấn riêng với những món đồ đặc biệt từ Local Brand.</p>
                                    <button type="button" style="background-color: #d9534f;"
                                        class="btn btn-default get">Mua ngay</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{('public/frontend/images/slide1.jfif')}}" class="girl img-responsive"
                                        alt="" />

                                </div>
                            </div>

                            <div class="item">
                                <div class="col-sm-6">
                                    <h1><span style="color: #AFEEEE;">LOCAL</span>-BRAND</h1>
                                    <h2>Shop Authentic Local Fashion</h2>
                                    <p>Khám phá các sản phẩm thời trang độc đáo từ các thương hiệu nội địa. Chúng tôi
                                        mang đến những thiết kế sáng tạo, chất lượng cao, hoàn hảo cho phong cách sống
                                        hiện đại. Hãy tạo nên dấu ấn riêng với những món đồ đặc biệt từ Local Brand.</p>
                                    <button type="button" style="background-color: #d9534f;"
                                        class="btn btn-default get">Mua ngay</button>
                                </div>
                                <div class="col-sm-6">
                                    <img src="{{('public/frontend/images/slide2.webp')}}" class="girl img-responsive"
                                        alt="" />

                                </div>
                            </div>

                        </div>

                        <a href="#slider-carousel" class="left control-carousel hidden-xs" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a href="#slider-carousel" class="right control-carousel hidden-xs" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!--/slider-->

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    <div class="left-sidebar">
                        <h2 style="color: #d9534f;">Danh mục Sản phẩm</h2>
                        @foreach($categories as $category)
                        <div class="panel-group category-products" id="accordian">
                            <!--category-productsr-->
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a href="{{ url('/danh-muc-san-pham/'.$category->category_id)}}">{{ $category->category_name }}
                                        </a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <!--/category-products-->

                        <div class="brands_products">
                            <!--brands_products-->
                            <h2 style="color: #d9534f;">Thương Hiệu sản phẩm</h2>
                            <div class="brands-name">

                                <ul class="nav nav-pills nav-stacked">
                                    @foreach($brands as $brand)
                                    <li><a href="{{ url('/thuong-hieu-san-pham/'.$brand->brand_id)}}"> <span
                                                class="pull-right">(10)</span>{{$brand->brand_name}}</a>
                                    </li>
                                    @endforeach


                                </ul>
                            </div>
                        </div>
                        <!--/brands_products-->

                        <!-- <div class="price-range">
                           
                            <h2>Price Range</h2>
                            <div class="well text-center">
                                <input type="text" class="span2" value="" data-slider-min="0" data-slider-max="600"
                                    data-slider-step="5" data-slider-value="[250,450]" id="sl2"><br />
                                <b class="pull-left">$ 0</b> <b class="pull-right">$ 600</b>
                            </div>
                        </div> -->
                        <!--/price-range-->

                        <!-- <div class="shipping text-center">
                            
                            <img src="{{('public/frontend/images/shipping.jpg')}}" alt="" />
                        </div> -->
                        <!--/shipping-->

                    </div>
                </div>

                <div class="col-sm-9 padding-right">
                    <!-- call home -->
                    @yield('content')


                </div>
            </div>
        </div>
    </section>

    <footer id="footer">
        <!--Footer-->


        <div class="footer-widget">
            <div class="container">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Dịch vụ</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Hỗ trợ trực tiếp</a></li>
                                <li><a href="#">Liên hệ với chúng tôi</a></li>
                                <li><a href="#">Trạng thái đặt hàng</a></li>
                                <li><a href="#">thay đổi thông tin</a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Cửa hàng</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">Áo</a></li>
                                <li><a href="#">Đàn ông </a></li>
                                <li><a href="#">Phụ nữ</a></li>
                                <li><a href="#">thẻ quà tặng</a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>yêu cầu</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#"></a></li>
                                <li><a href="#">hệ thống đăng nhập</a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="single-widget">
                            <h2>Về cửa hàng</h2>
                            <ul class="nav nav-pills nav-stacked">
                                <li><a href="#">thông tin công ty</a></li>
                                <li><a href="#">Công việc</a></li>
                                <li><a href="#">Vị trí cửa hàng</a></li>


                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-3 col-sm-offset-1">
                        <div class="single-widget">
                            <h2>Thông tin về cửa hàng</h2>
                            <form action="#" class="searchform">
                                <input type="text" placeholder="Địa chỉ email của bạn" />
                                <button type="submit" class="btn btn-default"><i
                                        class="fa fa-arrow-circle-o-right"></i></button>
                                <p>Nhận những cập nhật mới nhất từ<br />trang web của chúng tôi và tự cập nhật...</p>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <div class="row">

                </div>
            </div>
        </div>

    </footer>
    <!--/Footer-->

    <script src="{{asset('public/frontend/js/jquery.js')}}"></script>
    <script src="{{asset('public/frontend/js/bootstrap.min.js')}} "></script>
    <script src="{{asset('public/frontend/js/jquery.scrollUp.min.js')}} "></script>
    <script src="{{asset('public/frontend/js/price-range.js')}}"></script>
    <script src="{{asset('public/frontend/js/jquery.prettyPhoto.js')}} "></script>
    <script src="{{asset('public/frontend/js/main.js')}} "></script>



</body>

</html>