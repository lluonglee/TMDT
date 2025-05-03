@extends('layout')
@section('content')
<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs">
            <ol class="breadcrumb">
                <li><a href="{{ URL('/') }}">Trang Chủ</a></li>
                <li class="active">Kết quả thanh toán</li>
            </ol>
        </div>
        <div class="review-payment">
            <h2>Kết quả thanh toán</h2>
        </div>
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
        </div>
        @elseif(Session::has('error'))
        <div class="alert alert-danger">
            {{ Session::get('error') }}
        </div>
        @endif
        <a href="{{ URL('/') }}" class="btn btn-primary">Quay về trang chủ</a>
        @if(Session::has('error'))
        <a href="{{ URL('/checkout') }}" class="btn btn-warning">Thử lại thanh toán</a>
        @endif
    </div>
</section>
@endsection