@extends('admin_layout')
@section('admin_content')

<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Chỉnh sửa sản phẩm
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <div class="position-center">
                    <form role="form" action="{{ url('/update-product/'.$product->product_id) }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Tên sản phẩm</label>
                            <input required class="form-control" name="product_name"
                                value="{{ $product->product_name }}">
                        </div>

                        <div class="form-group">
                            <label>Giá sản phẩm</label>
                            <input required type="number" class="form-control" name="product_price"
                                value="{{ $product->product_price }}">
                        </div>

                        <div class="form-group">
                            <label>Số lượng</label>
                            <input required type="number" class="form-control" name="product_quantity"
                                value="{{ $product->product_quantity }}">
                        </div>

                        <div class="form-group">
                            <label>Danh mục</label>
                            <select name="category_id" class="form-control">
                                @foreach($categories as $category)
                                <option value="{{ $category->category_id }}"
                                    {{ $product->category_id == $category->category_id ? 'selected' : '' }}>
                                    {{ $category->category_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Thương hiệu</label>
                            <select name="brand_id" class="form-control">
                                @foreach($brands as $brand)
                                <option value="{{ $brand->brand_id }}"
                                    {{ $product->brand_id == $brand->brand_id ? 'selected' : '' }}>
                                    {{ $brand->brand_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Mô tả sản phẩm</label>
                            <textarea required class="form-control" rows="5"
                                name="product_desc">{{ $product->product_desc }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>Trạng thái</label>
                            <select name="product_status" class="form-control">
                                <option value="1" {{ $product->product_status == 1 ? 'selected' : '' }}>Hiển thị
                                </option>
                                <option value="0" {{ $product->product_status == 0 ? 'selected' : '' }}>Ẩn</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Kích thước</label>
                            <input class="form-control" name="product_size" value="{{ $product->product_size }}">
                        </div>

                        <div class="form-group">
                            <label>Màu sắc</label>
                            <input class="form-control" name="product_color" value="{{ $product->product_color }}">
                        </div>

                        <div class="form-group">
                            <label>Chất liệu</label>
                            <input class="form-control" name="product_material"
                                value="{{ $product->product_material }}">
                        </div>

                        <div class="form-group">
                            <label>Giảm giá (%)</label>
                            <input type="number" class="form-control" name="discount" value="{{ $product->discount }}">
                        </div>

                        <div class="form-group">
                            <label>Hình ảnh sản phẩm</label>
                            <input type="file" name="product_image">
                            @if($product->product_image)
                            <img src="{{ url($product->product_image) }}" width="100">
                            @endif
                        </div>

                        <button type="submit" class="btn btn-info">Cập nhật sản phẩm</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>

@endsection