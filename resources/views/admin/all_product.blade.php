@extends('admin_layout')
@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Danh sách sản phẩm</header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên sản phẩm</th>
                            <th>Hình ảnh</th>
                            <th>Danh mục</th>
                            <th>Thương hiệu</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all_product as $product)
                        <tr>
                            <td>{{ $product->product_name }}</td>
                            <td>
                                <img src="{{ asset($product->product_image) }}" height="70" width="70">
                            </td>
                            <td>{{ $product->category_name }}</td>
                            <td>{{ $product->brand_name }}</td>
                            <td>{{ number_format($product->product_price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $product->product_quantity }}</td>
                            <td>
                                @if($product->product_status == 1)
                                <a href="{{ url('/unActive-product/'.$product->product_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn ẩn sản phẩm này?');">
                                    <span class="text-success">Hiển thị</span>
                                </a>
                                @else
                                <a href="{{ url('/active-product/'.$product->product_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn hiển thị sản phẩm này?');">
                                    <span class="text-danger">Ẩn</span>
                                </a>
                                @endif
                            </td>

                            <td>
                                <a style="font-size: 25px;" href="{{ url('/edit-product/'.$product->product_id) }}"
                                    class="active">
                                    <i class="fa fa-pencil-square-o text-success"></i>
                                </a>

                                <a style="font-size: 25px;" href="{{ url('/delete-product/'.$product->product_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');" class="active">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection