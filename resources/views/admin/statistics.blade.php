@extends('admin_layout')
@section('admin_content')

<div class="container">
    <h2 class="my-3">Thống Kê Đơn Hàng và Khách Hàng</h2>

    {{-- Form lọc --}}
    <form action="{{ url('/statistics') }}" method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-auto">
            <label for="filter_type" class="form-label">Loại bộ lọc</label>
            <select name="filter_type" id="filter_type" class="form-control" onchange="toggleInputs()">
                <option value="range" {{ $filter_type === 'range' ? 'selected' : '' }}>Khoảng thời gian</option>
                <option value="day" {{ $filter_type === 'day' ? 'selected' : '' }}>Ngày</option>
                <option value="week" {{ $filter_type === 'week' ? 'selected' : '' }}>Tuần</option>
                <option value="year" {{ $filter_type === 'year' ? 'selected' : '' }}>Năm</option>
            </select>
        </div>
        <div class="col-auto" id="range_inputs" style="display: {{ $filter_type === 'range' ? 'block' : 'none' }}">
            <label for="start_date" class="form-label">Từ ngày</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ $start_date ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}">
            <label for="end_date" class="form-label mt-2">Đến ngày</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                value="{{ $end_date ?? \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}">
        </div>
        <div class="col-auto" id="day_input" style="display: {{ $filter_type === 'day' ? 'block' : 'none' }}">
            <label for="day" class="form-label">Ngày</label>
            <input type="date" name="day" id="day" class="form-control"
                value="{{ $day ?? \Carbon\Carbon::now()->format('Y-m-d') }}">
        </div>
        <div class="col-auto" id="week_input" style="display: {{ $filter_type === 'week' ? 'block' : 'none' }}">
            <label for="week" class="form-label">Tuần</label>
            <input type="week" name="week" id="week" class="form-control"
                value="{{ $week ?? \Carbon\Carbon::now()->format('Y-W') }}">
        </div>
        <div class="col-auto" id="year_input" style="display: {{ $filter_type === 'year' ? 'block' : 'none' }}">
            <label for="year" class="form-label">Năm</label>
            <input type="number" name="year" id="year" class="form-control" min="2000" max="2100"
                value="{{ $year ?? \Carbon\Carbon::now()->format('Y') }}">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Thống Kê</button>
        </div>
    </form>

    {{-- Thông báo --}}
    @if(Session::has('message'))
    <div class="alert alert-warning">{{ Session::get('message') }}</div>
    @endif

    {{-- Tổng quan --}}
    <h4 class="mb-3 section-title">Tổng Quan</h4>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success stats-card">
                <div class="card-body">
                    <h5 class="card-title">Tổng doanh thu</h5>
                    <p class="card-text text-success fw-bold">
                        {{ isset($total_revenue) ? number_format($total_revenue, 0, ',', '.') : '0' }} VNĐ
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info stats-card">
                <div class="card-body">
                    <h5 class="card-title">Tổng phí vận chuyển</h5>
                    <p class="card-text text-info fw-bold">
                        {{ isset($total_shipping_fee) ? number_format($total_shipping_fee, 0, ',', '.') : '0' }} VNĐ
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-dark stats-card">
                <div class="card-body">
                    <h5 class="card-title">Tổng số đơn hàng</h5>
                    <p class="card-text fw-bold">
                        {{ isset($total_orders) ? $total_orders : '0' }} đơn
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Thống kê tài khoản khách hàng --}}
    <h4 class="mb-3 section-title">Thống Kê Tài Khoản Khách Hàng</h4>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-primary stats-card">
                <div class="card-body">
                    <h5 class="card-title">Tổng số khách hàng</h5>
                    <p class="card-text text-primary fw-bold">
                        {{ $customer_stats['total_customers'] ?? 0 }} khách hàng
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning stats-card">
                <div class="card-body">
                    <h5 class="card-title">Khách hàng mới</h5>
                    <p class="card-text text-warning fw-bold">
                        {{ $customer_stats['new_customers'] ?? 0 }} khách hàng
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success stats-card">
                <div class="card-body">
                    <h5 class="card-title">Khách hàng có đơn hàng</h5>
                    <p class="card-text text-success fw-bold">
                        {{ $customer_stats['active_customers'] ?? 0 }} khách hàng
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Thống kê trạng thái đơn --}}
    <h4 class="mb-3 section-title">Trạng Thái Đơn Hàng</h4>
    <div class="row mb-4">
        @if($status_stats->isNotEmpty())
        @foreach($status_stats as $stat)
        <div class="col-md-3">
            <div class="card border-secondary stats-card">
                <div class="card-body">
                    <h6 class="card-title">Trạng thái: {{ $stat->order_status }}</h6>
                    <p class="mb-1">Số đơn: <strong>{{ $stat->order_count }}</strong></p>
                    <p class="mb-1">Doanh thu: <strong>{{ number_format($stat->total_revenue, 0, ',', '.') }}
                            VNĐ</strong></p>
                    <p class="mb-0">Phí ship: <strong>{{ number_format($stat->total_shipping_fee, 0, ',', '.') }}
                            VNĐ</strong></p>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <p class="text-muted">Không có dữ liệu thống kê theo trạng thái.</p>
        @endif
    </div>

    {{-- Biểu đồ doanh thu theo tháng --}}
    <h4 class="mb-3 section-title">Biểu Đồ Doanh Thu Theo Tháng</h4>
    <div class="card mb-4 stats-card">
        <div class="card-body">
            @if(!empty($chart_labels))
            <canvas id="revenueChart" height="100" data-labels="{{ json_encode($chart_labels) }}"
                data-values="{{ json_encode($chart_data) }}">
            </canvas>
            @else
            <p class="text-muted">Không có dữ liệu để hiển thị biểu đồ.</p>
            @endif
        </div>
    </div>

    {{-- Top sản phẩm bán chạy --}}
    <h4 class="mb-3 section-title">Top 5 Sản Phẩm Bán Chạy</h4>
    <table class="table table-bordered table-stats">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng bán</th>
                <th>Doanh thu</th>
            </tr>
        </thead>
        <tbody>
            @if($top_products->isNotEmpty())
            @foreach($top_products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->total_quantity }}</td>
                <td>{{ number_format($product->total_revenue, 0, ',', '.') }} VNĐ</td>
            </tr>
            @endforeach
            @else
            <p class="text-muted">Không có dữ liệu sản phẩm bán chạy.</p>
            @endif
        </tbody>
    </table>
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

    .form-control {
        border-radius: 5px;
        border: 1px solid #ced4da;
        padding: 8px;
        font-size: 0.9rem;
        transition: border-color 0.3s ease;
    }

    .form-control:focus {
        border-color: #007BFF;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .btn-primary {
        background-color: #007BFF;
        border: none;
        padding: 8px 20px;
        font-size: 0.9rem;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .alert-warning {
        border-radius: 5px;
        padding: 12px;
        font-size: 0.9rem;
        background-color: #fff3cd;
        border: 1px solid #ffeeba;
        color: #856404;
    }

    .stats-card {
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        background-color: #fff;
    }

    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stats-card .card-body {
        padding: 20px;
    }

    .stats-card .card-title {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 10px;
    }

    .stats-card .card-text {
        font-size: 1.2rem;
        margin: 0;
    }

    .table-stats {
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .table-stats th,
    .table-stats td {
        padding: 12px;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .table-stats th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .table-stats tbody tr:hover {
        background-color: #f1f3f5;
    }

    .text-muted {
        font-size: 0.9rem;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .container {
            padding: 15px;
        }

        .section-title {
            font-size: 1.3rem;
        }

        .stats-card {
            margin-bottom: 15px;
        }

        .stats-card .card-title {
            font-size: 1rem;
        }

        .stats-card .card-text {
            font-size: 1.1rem;
        }

        .form-control,
        .btn-primary {
            font-size: 0.85rem;
        }

        .table-stats th,
        .table-stats td {
            font-size: 0.85rem;
            padding: 10px;
        }
    }

    @media (max-width: 576px) {

        .col-md-4,
        .col-md-3 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        .stats-card {
            margin-bottom: 10px;
        }

        .section-title {
            font-size: 1.2rem;
        }
    }
</style>

{{-- ChartJS script --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    function toggleInputs() {
        const filterType = document.getElementById('filter_type').value;
        document.getElementById('range_inputs').style.display = filterType === 'range' ? 'block' : 'none';
        document.getElementById('day_input').style.display = filterType === 'day' ? 'block' : 'none';
        document.getElementById('week_input').style.display = filterType === 'week' ? 'block' : 'none';
        document.getElementById('year_input').style.display = filterType === 'year' ? 'block' : 'none';
    }

    // Khởi tạo trạng thái ban đầu
    toggleInputs();

    const canvas = document.getElementById('revenueChart');
    if (canvas) {
        const chartLabels = JSON.parse(canvas.dataset.labels);
        const chartData = JSON.parse(canvas.dataset.values);

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Doanh Thu Theo Tháng (VNĐ)',
                    data: chartData,
                    borderColor: '#007BFF',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Doanh Thu (VNĐ)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Tháng'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    }
</script>
@endsection