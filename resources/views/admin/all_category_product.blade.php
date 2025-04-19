@extends('admin_layout')

@section('admin_content')

<div class="table-agile-info">
    <div class="panel panel-default">
        <div class="panel-heading">
            liệt kê danh mục sản phẩm
        </div>
        <!-- <div class="row w3-res-tb">
            <div class="col-sm-5 m-b-xs">
                <select class="input-sm form-control w-sm inline v-middle">
                    <option value="0">Bulk action</option>
                    <option value="1">Delete selected</option>
                    <option value="2">Bulk edit</option>
                    <option value="3">Export</option>
                </select>
                <button class="btn btn-sm btn-default">Apply</button>
            </div>
            <div class="col-sm-4">
            </div>
            <div class="col-sm-3">
                <div class="input-group">
                    <input type="text" class="input-sm form-control" placeholder="Search">
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button">Go!</button>
                    </span>
                </div>
            </div>
        </div> -->
        <div class="table-responsive">
            <table class="table table-striped b-t b-light">
                <thead>
                    <tr>
                        <!-- <th style="width:20px;">
                            <label class="i-checks m-b-none">
                                <input type="checkbox"><i></i>
                            </label>
                        </th> -->
                        <th>Tên danh mục</th>
                        <th>Hiển thị</th>
                        <th>Ngày thêm</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($all_category_product as $category)
                    <tr>
                        <!-- <td>
                            <label class="i-checks m-b-none">
                                <input type="checkbox" name="post[]"><i></i>
                            </label>
                        </td> -->
                        <td>{{ $category->category_name }}</td>
                        <td>
                            <span class="text-ellipsis">
                                @if($category->category_status == 1)
                                <!-- Khi click vào sẽ chuyển về trạng thái Ẩn -->
                                <a href="{{ url('/unActive-category-product/'.$category->category_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn ẩn danh mục này?');">
                                    <span class="text-success">Hiển thị</span>
                                </a>
                                @else
                                <!-- Khi click vào sẽ chuyển về trạng thái Hiển thị -->
                                <a href="{{ url('/active-category-product/'.$category->category_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn hiển thị danh mục này?');">
                                    <span class="text-danger">Ẩn</span>
                                </a>
                                @endif
                            </span>
                        </td>
                        <td><span class="text-ellipsis">{{ date('d/m/Y') }}</span></td>
                        <td>
                            <a style="font-size: 25px;"
                                href="{{ url('/edit-category-product/'.$category->category_id) }}" class="active">
                                <i class="fa fa-pencil-square-o text-success"></i>
                            </a>

                            <a style="font-size: 25px;"
                                href="{{ url('/delete-category-product/'.$category->category_id) }}"
                                onclick="return confirm('Bạn có chắc muốn xóa danh mục này?');" class="active">
                                <i class="fa fa-trash text-danger"></i>
                            </a>

                        </td>
                    </tr>
                    @endforeach

                </tbody>

            </table>
        </div>
        <footer class="panel-footer">
            <!-- <div class="row">

                <div class="col-sm-5 text-center">
                    <small class="text-muted inline m-t-sm m-b-sm">showing 20-30 of 50 items</small>
                </div>
                <div class="col-sm-7 text-right text-center-xs">
                    <ul class="pagination pagination-sm m-t-none m-b-none">
                        <li><a href=""><i class="fa fa-chevron-left"></i></a></li>
                        <li><a href="">1</a></li>
                        <li><a href="">2</a></li>
                        <li><a href="">3</a></li>
                        <li><a href="">4</a></li>
                        <li><a href=""><i class="fa fa-chevron-right"></i></a></li>
                    </ul>
                </div>
            </div> -->
        </footer>
    </div>
</div>
<style>
.table th,
.table td {
    color: #000 !important;
}
</style>
@endsection