@extends('admin_layout')
@section('admin_content')
<div class="container">
    <h2 class="section-title">Quản lý phí ship</h2>
    <a href="{{ route('shipping_fees.create') }}" class="btn btn-primary mb-3 btn-custom">Thêm phí ship</a>
    <form action="{{ route('shipping_fees.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control search-input" placeholder="Tìm theo tỉnh/quận"
                value="{{ $search }}">
            <div class="input-group-append">
                <button type="submit" class="btn btn-primary btn-custom">Tìm</button>
            </div>
        </div>
    </form>
    @if(session('success'))
    <div class="alert alert-success alert-custom">{{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-custom">{{ session('error') }}</div>
    @endif
    <table class="table table-bordered table-custom">
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
            <tr style="font-weight: bold; color: black;">
                <td>{{ $fee->province_name }}</td>
                <td>{{ $fee->district_name ?? 'Cả tỉnh' }}</td>
                <td>{{ number_format($fee->fee, 0, ',', '.') }}</td>
                <td>
                    <a href="{{ route('shipping_fees.edit', $fee->id) }}"
                        class="btn btn-sm btn-warning btn-action">Sửa</a>
                    <form action="{{ route('shipping_fees.destroy', $fee->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-action"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center text-muted">Không có dữ liệu</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-custom">
        {{ $shippingFees->links() }}
    </div>
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        border-bottom: 2px solid #007BFF;
        padding-bottom: 8px;
        margin-bottom: 20px;
    }

    .btn-custom {
        border-radius: 5px;
        padding: 8px 20px;
        font-size: 0.9rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-custom:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }

    .btn-primary.btn-custom {
        background-color: #007BFF;
        border: none;
    }

    .search-input {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 8px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
    }

    .search-input:focus {
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
        outline: none;
    }

    .alert-custom {
        border-radius: 5px;
        padding: 12px;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        border: 1px solid #c3e6cb;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        border: 1px solid #f5c6cb;
        color: #721c24;
    }

    .table-custom {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .table-custom th,
    .table-custom td {
        padding: 12px;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .table-custom th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .table-custom tbody tr:hover {
        background-color: #f1f3f5;
    }

    .btn-action {
        padding: 5px 15px;
        font-size: 0.85rem;
        border-radius: 4px;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .btn-action:hover {
        transform: translateY(-2px);
    }

    .btn-warning.btn-action {
        background-color: #ffc107;
        border: none;
    }

    .btn-warning.btn-action:hover {
        background-color: #e0a800;
    }

    .btn-danger.btn-action {
        background-color: #dc3545;
        border: none;
    }

    .btn-danger.btn-action:hover {
        background-color: #c82333;
    }

    .text-muted {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .pagination-custom {
        margin-top: 20px;
    }

    .pagination-custom .pagination {
        justify-content: center;
    }

    .pagination-custom .page-link {
        border-radius: 4px;
        margin: 0 3px;
        font-size: 0.9rem;
        color: #007BFF;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .pagination-custom .page-link:hover {
        background-color: #007BFF;
        color: #fff;
    }

    .pagination-custom .page-item.active .page-link {
        background-color: #007BFF;
        border-color: #007BFF;
        color: #fff;
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        .section-title {
            font-size: 1.3rem;
        }

        .btn-custom,
        .search-input {
            font-size: 0.85rem;
        }

        .table-custom th,
        .table-custom td {
            font-size: 0.85rem;
            padding: 10px;
        }

        .btn-action {
            padding: 4px 12px;
            font-size: 0.8rem;
        }

        .alert-custom {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {

        .table-custom th,
        .table-custom td {
            font-size: 0.8rem;
            padding: 8px;
        }

        .btn-action {
            display: block;
            width: 100%;
            margin-bottom: 5px;
        }

        .input-group {
            flex-direction: column;
        }

        .input-group-append {
            width: 100%;
        }

        .btn-custom {
            width: 100%;
        }


    }
</style>
@endsection