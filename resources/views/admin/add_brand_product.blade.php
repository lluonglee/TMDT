@extends('admin_layout')

@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Thêm Thương Hiệu Sản Phẩm
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    {{ Session::get('message') }}
                </div>
                @endif
                <div class="position-center">
                    <form role="form" action="{{ url('/save-brand-product') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tên Thương Hiệu</label>
                            <input required class="form-control" name="brand_product_name" id="exampleInputEmail1"
                                placeholder="Tên danh mục">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mô tả Thương Hiệu</label>
                            <textarea style="resize: none; " rows="5" class="form-control" name="brand_product_desc"
                                required id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Hiển thị/Ẩn</label>
                            <select name="brand_product_status" class="form-control input-lg m-bot15">

                                <option value="0">Ẩn </option>
                                <option value="1">Hiển thị</option>

                            </select>

                        </div>
                        <button type="submit" name="add-brand-Product" class="btn btn-info">Thêm thương hiệu</button>
                    </form>
                </div>


            </div>

    </div>
    </section>

</div>

@endsection