@extends('admin_layout')
@section('admin_content')
<div class="container">
    <h2>Quản Lý Mã Khuyến Mãi</h2>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary mb-3">Thêm mã khuyến mãi</a>

    @if(Session::has('success'))
    <div class="alert alert-success">{{ Session::get('success') }}</div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mã</th>
                <th>Giá trị giảm</th>
                <th>Loại</th>
                <th>Giới hạn tối đa</th>
                <th>Ngày bắt đầu</th>
                <th>Ngày kết thúc</th>
                <th>Số lần dùng</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($promotions as $promotion)
            <tr>
                <td>{{ $promotion->id }}</td>
                <td>{{ $promotion->code }}</td>
                <td>
                    {{ $promotion->discount_type == 'fixed' ? number_format($promotion->discount_value, 0, ',', '.') . ' VNĐ' : $promotion->discount_value . '%' }}
                </td>
                <td>{{ $promotion->discount_type == 'fixed' ? 'Cố định' : 'Phần trăm' }}</td>
                <td>{{ $promotion->max_discount ? number_format($promotion->max_discount, 0, ',', '.') . ' VNĐ' : 'Không giới hạn' }}
                </td>
                <td>{{ \Carbon\Carbon::parse($promotion->start_date)->format('d/m/Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($promotion->end_date)->format('d/m/Y') }}</td>
                <td>{{ $promotion->used_count }} /
                    {{ $promotion->usage_limit == 0 ? 'Không giới hạn' : $promotion->usage_limit }}
                </td>
                <td>{{ $promotion->is_active ? 'Hoạt động' : 'Ngừng' }}</td>
                <td>
                    <a href="{{ route('admin.promotions.edit', $promotion->id) }}"
                        class="btn btn-sm btn-primary">Sửa</a>
                    <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $promotions->links() }}
</div>
<style>
.table th,
.table td {
    color: #000 !important;
}
</style>
@endsection