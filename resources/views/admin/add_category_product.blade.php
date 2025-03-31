@extends('admin_layout')

@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Thêm danh mục sản phẩm
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    {{ Session::get('message') }}
                </div>
                @endif
                <div class="position-center">
                    <form role="form" action="{{ url('/save-category-product') }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tên danh mục</label>
                            <input required class="form-control" name="category-product-name" id="exampleInputEmail1"
                                placeholder="Tên danh mục">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mô tả danh mục</label>
                            <textarea style="resize: none; " rows="5" class="form-control" name="category_product_desc"
                                required id="exampleInputPassword1" placeholder="Mô tả danh mục"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Hiển thị/Ẩn</label>

                            <select name="category-product-status" class="form-control input-lg m-bot15">

                                <option value="0">Ẩn </option>
                                <option value="1">Hiển thị</option>

                            </select>

                        </div>
                        <button type="submit" name="add_category_product" class="btn btn-info">Thêm danh mục</button>
                    </form>
                </div>


            </div>

    </div>
    </section>

</div>



@endsection