@extends('admin_layout')
@section('admin_content')
<div class="container">
    <h2>Thêm phí ship</h2>
    <form action="{{ route('shipping_fees.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="matp">Tỉnh/Thành phố</label>
            <select name="matp" id="matp" class="form-control" required>
                <option value="">Chọn tỉnh/thành phố</option>
                @foreach($provinces as $province)
                <option value="{{ $province->matp }}">{{ $province->name }}</option>
                @endforeach
            </select>
            @error('matp')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="maqh">Quận/Huyện (Để trống nếu áp dụng cho cả tỉnh)</label>
            <select name="maqh" id="maqh" class="form-control">
                <option value="">Cả tỉnh</option>
            </select>
            @error('maqh')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="fee">Phí ship (VNĐ)</label>
            <input type="number" name="fee" id="fee" class="form-control" required min="0">
            @error('fee')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary add_dilivery">Thêm</button>
        <a href="{{ route('shipping_fees.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection