@extends('admin_layout')
@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">
                Cập nhật danh mục sản phẩm
            </header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div style="color: red; text-align: center; margin-bottom: 10px;">
                    {{ Session::get('message') }}
                </div>
                @endif

                <div class="position-center">
                    <form role="form" action="{{ url('/update-brand-product/'.$brand->brand_id) }}" method="post">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="exampleInputEmail1">Tên nhãn hàng</label>
                            <input type="text" class="form-control" name="brand_product_name" id="exampleInputEmail1"
                                value="{{ $brand->brand_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Mô tả nhãn hàng</label>
                            <textarea style="resize: none;" rows="5" class="form-control" name="brand_product_desc"
                                required id="exampleInputPassword1">{{ $brand->brand_desc }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-info">Cập nhật danh mục</button>

                    </form>
                </div>

            </div>

    </div>
    </section>

</div>
@endsection