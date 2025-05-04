@extends('admin_layout')
@section('admin_content')
<div class="container">
    <h2 class="section-title">Quản Lý Mã Khuyến Mãi</h2>
    <a href="{{ route('admin.promotions.create') }}" class="btn btn-primary mb-3 btn-custom">Thêm mã khuyến mãi</a>

    @if(Session::has('success'))
    <div class="alert alert-success alert-custom">{{ Session::get('success') }}</div>
    @endif
    @if(Session::has('error'))
    <div class="alert alert-danger alert-custom">{{ Session::get('error') }}</div>
    @endif

    <table class="table table-bordered table-custom">
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
                        class="btn btn-sm btn-primary btn-action">Sửa</a>
                    <form action="{{ route('admin.promotions.destroy', $promotion->id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger btn-action"
                            onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="pagination-custom">
        {{ $promotions->links() }}
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
        color: #000 !important;
        /* Giữ nguyên style hiện tại */
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

    .btn-primary.btn-action {
        background-color: #007BFF;
        border: none;
    }

    .btn-primary.btn-action:hover {
        background-color: #0056b3;
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

        .btn-custom {
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
        .table-custom {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }

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

        .btn-custom {
            width: 100%;
        }
    }
</style>
@endsection