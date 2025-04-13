@extends('admin_layout')

@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Thêm Sản Phẩm
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    {{ Session::get('message') }}
                </div>
                @endif
                <div class="position-center">
                    <form role="form" action="{{ url('/save-Product') }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label>Tên Sản Phẩm</label>
                            <input required class="form-control" name="product_name" placeholder="Nhập tên sản phẩm">
                        </div>

                        <div class="form-group">
                            <label>Danh Mục</label>

                            <select name="category_id" class="form-control">

                                @foreach($categories as $category)
                                <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Thương Hiệu</label>
                            <select name="brand_id" class="form-control">

                                @foreach($brands as $brand)
                                <option value="{{ $brand->brand_id }}">{{ $brand->brand_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Giá Sản Phẩm</label>
                            <input required type="number" class="form-control" name="product_price"
                                placeholder="Nhập giá sản phẩm">
                        </div>

                        <div class="form-group">
                            <label>Số Lượng</label>
                            <input required type="number" class="form-control" name="product_quantity"
                                placeholder="Nhập số lượng">
                        </div>

                        <div class="form-group">
                            <label>Mô tả Sản Phẩm</label>
                            <textarea style="resize: none;" rows="5" class="form-control" name="product_desc"
                                placeholder="Mô tả sản phẩm"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Hình Ảnh</label>
                            <input type="file" class="form-control" name="product_image">
                        </div>

                        <div class="form-group">
                            <label>Size</label>
                            <input class="form-control" name="product_size" placeholder="Nhập kích thước">
                        </div>

                        <div class="form-group">
                            <label>Màu Sắc</label>
                            <input class="form-control" name="product_color" placeholder="Nhập màu sắc">
                        </div>

                        <div class="form-group">
                            <label>Chất Liệu</label>
                            <input class="form-control" name="product_material" placeholder="Nhập chất liệu">
                        </div>

                        <div class="form-group">
                            <label>Giảm Giá (%)</label>
                            <input type="number" class="form-control" name="discount" placeholder="Nhập giảm giá">
                        </div>

                        <div class="form-group">
                            <label>Hiển thị/Ẩn</label>
                            <select name="product_status" class="form-control">
                                <option value="0">Ẩn</option>
                                <option value="1">Hiển thị</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-info">Thêm Sản Phẩm</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection