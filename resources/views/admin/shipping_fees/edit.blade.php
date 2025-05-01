@extends('admin_layout')

@section('admin_content')
<div class="container">
    <h2>Sửa phí ship</h2>
    <form action="{{ route('shipping_fees.update', $shippingFee->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="matp">Tỉnh/Thành phố</label>
            <select name="matp" id="matp" class="form-control" required>
                <option value="">Chọn tỉnh/thành phố</option>
                @foreach($provinces as $province)
                <option value="{{ $province->matp }}" {{ $province->matp == $shippingFee->matp ? 'selected' : '' }}>
                    {{ $province->name }}
                </option>
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
                @foreach($districts as $district)
                <option value="{{ $district->maqh }}" {{ $district->maqh == $shippingFee->maqh ? 'selected' : '' }}>
                    {{ $district->name }}
                </option>
                @endforeach
            </select>
            @error('maqh')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group">
            <label for="fee">Phí ship (VNĐ)</label>
            <input type="number" name="fee" id="fee" class="form-control" value="{{ $shippingFee->fee }}" required
                min="0">
            @error('fee')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('shipping_fees.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>
@endsection