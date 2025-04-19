@extends('admin_layout')
@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <div class="container">

                <header class="panel-heading">Quản Lý Đánh Giá</header>

                @if(Session::has('success'))
                <div class="alert alert-success">{{ Session::get('success') }}</div>
                @endif

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Sản Phẩm</th>
                            <th>Khách Hàng</th>
                            <th>Email</th>
                            <th>Số Sao</th>
                            <th>Bình Luận</th>
                            <th>Trạng Thái</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->product_name }}</td>
                            <td>{{ $review->customer_name }}</td>
                            <td>{{ $review->customer_email }}</td>
                            <td>{{ $review->rating }} ⭐</td>
                            <td>{{ $review->comment ?? 'Không có bình luận' }}</td>
                            <td>
                                <span class="status-{{ $review->status }}">
                                    {{ $review->status == 'pending' ? 'Chờ duyệt' : ($review->status == 'approved' ? 'Đã duyệt' : 'Bị từ chối') }}
                                </span>
                            </td>
                            <td>
                                <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" {{ $review->status == 'pending' ? 'selected' : '' }}>Chờ
                                            duyệt
                                        </option>
                                        <option value="approved" {{ $review->status == 'approved' ? 'selected' : '' }}>
                                            Duyệt
                                        </option>
                                        <option value="rejected" {{ $review->status == 'rejected' ? 'selected' : '' }}>
                                            Từ chối
                                        </option>
                                    </select>
                                </form>
                                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $reviews->links() }}
            </div>
    </div>
</div>
<style>
.table th,
.table td {
    color: #000 !important;
}
</style>
@endsection