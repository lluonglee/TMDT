@extends('admin_layout')
@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Danh sách thương hiệu</header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Tên thương hiệu</th>
                            <th>Mô tả</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($all_brand_product as $brand)
                        <tr>
                            <td>{{ $brand->brand_name }}</td>
                            <td>{{ $brand->brand_desc }}</td>
                            <td>
                                <span class="text-ellipsis">
                                    @if($brand->brand_status == 1)
                                    <!-- Khi click vào sẽ chuyển về trạng thái Ẩn -->
                                    <a href="{{ url('/unActive-brand-product/'.$brand->brand_id) }}"
                                        onclick="return confirm('Bạn có chắc muốn ẩn danh mục này?');">
                                        <span class="text-success">Hiển thị</span>
                                    </a>
                                    @else
                                    <!-- Khi click vào sẽ chuyển về trạng thái Hiển thị -->
                                    <a href="{{ url('/active-brand-product/'.$brand->brand_id) }}"
                                        onclick="return confirm('Bạn có chắc muốn hiển thị danh mục này?');">
                                        <span class="text-danger">Ẩn</span>
                                    </a>
                                    @endif
                                </span>
                            </td>


                            <td>
                                <a style="font-size: 25px;" href="{{ url('/edit-brand-product/'.$brand->brand_id) }}"
                                    class="active">
                                    <i class="fa fa-pencil-square-o text-success"></i>
                                </a>

                                <a style="font-size: 25px;" href="{{ url('/delete-brand-product/'.$brand->brand_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn xóa danh mục này?');" class="active">
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
<style>
.table th,
.table td {
    color: #000 !important;
}
</style>
@endsection