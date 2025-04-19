@extends('admin_layout')

@section('admin_content')
<div class="container">
    <h2>Sửa Mã Khuyến Mãi</h2>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.promotions.update', $promotion->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="code">Mã khuyến mãi</label>
            <input type="text" name="code" id="code" class="form-control" value="{{ $promotion->code }}" required>
        </div>
        <div class="form-group">
            <label for="discount_value">Giá trị giảm</label>
            <input type="number" name="discount_value" id="discount_value" class="form-control" step="0.01" min="0"
                value="{{ $promotion->discount_value }}" required>
        </div>
        <div class="form-group">
            <label for="discount_type">Loại giảm giá</label>
            <select name="discount_type" id="discount_type" class="form-control" required>
                <option value="fixed" {{ $promotion->discount_type == 'fixed' ? 'selected' : '' }}>Cố định (VNĐ)
                </option>
                <option value="percentage" {{ $promotion->discount_type == 'percentage' ? 'selected' : '' }}>Phần trăm
                    (%)</option>
            </select>
        </div>
        <div class="form-group">
            <label for="max_discount">Giới hạn tối đa (nếu là %)</label>
            <input type="number" name="max_discount" id="max_discount" class="form-control" step="0.01" min="0"
                value="{{ $promotion->max_discount ?? '' }}">
        </div>
        <div class="form-group">
            <label for="start_date">Ngày bắt đầu</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ $promotion->start_date }}" required>
        </div>
        <div class="form-group">
            <label for="end_date">Ngày kết thúc</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $promotion->end_date }}"
                required>
        </div>
        <div class="form-group">
            <label for="usage_limit">Giới hạn sử dụng (0 = không giới hạn)</label>
            <input type="number" name="usage_limit" id="usage_limit" class="form-control" min="0"
                value="{{ $promotion->usage_limit }}" required>
        </div>
        <div class="form-group">
            <label for="is_active">Trạng thái</label>
            <select name="is_active" id="is_active" class="form-control">
                <option value="1" {{ $promotion->is_active ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ !$promotion->is_active ? 'selected' : '' }}>Ngừng</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('admin.promotions.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection