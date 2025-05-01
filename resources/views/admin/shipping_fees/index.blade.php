@extends('admin_layout')
@section('admin_content')
<div class="container">
    <h2>Quản lý phí ship</h2>
    <a href=" {{ route('shipping_fees.create') }}" class="btn btn-primary mb-3">Thêm phí ship</a>
    <form action="{{ route('shipping_fees.index') }}" method="GET" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Tìm theo tỉnh/quận"
                value="{{ $search }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary">Tìm</button>
            </div>
        </div>
    </form>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tỉnh/Thành phố</th>
                <th>Quận/Huyện</th>
                <th>Phí ship (VNĐ)</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($shippingFees as $fee)
            <tr>
                <td>{{ $fee->province_name }}</td>
                <td>{{ $fee->district_name ?? 'Cả tỉnh' }}</td>
                <td>{{ number_format($fee->fee, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('shipping_fees.edit', $fee->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                    <form action="{{ route('shipping_fees.destroy', $fee->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center">Không có dữ liệu</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    {{ $shippingFees->links() }}
</div>
@endsection