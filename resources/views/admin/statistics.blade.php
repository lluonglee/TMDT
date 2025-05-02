@extends('admin_layout')
@section('admin_content')

<div class="container">
    <h2 class="my-3">Thống Kê Đơn Hàng</h2>

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
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-success">
                <div class="card-body">
                    <h5 class="card-title">Tổng doanh thu</h5>
                    <p class="card-text text-success fw-bold">
                        {{ isset($total_revenue) ? number_format($total_revenue, 0, ',', '.') : '0' }} VNĐ
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body">
                    <h5 class="card-title">Tổng phí vận chuyển</h5>
                    <p class="card-text text-info fw-bold">
                        {{ isset($total_shipping_fee) ? number_format($total_shipping_fee, 0, ',', '.') : '0' }} VNĐ
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-dark">
                <div class="card-body">
                    <h5 class="card-title">Tổng số đơn hàng</h5>
                    <p class="card-text fw-bold">
                        {{ isset($total_orders) ? $total_orders : '0' }} đơn
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Thống kê trạng thái đơn --}}
    <h4 class="mb-3">Trạng Thái Đơn Hàng</h4>
    <div class="row mb-4">
        @if($status_stats->isNotEmpty())
        @foreach($status_stats as $stat)
        <div class="col-md-3">
            <div class="card border-secondary">
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
    <h4 class="mb-3">Biểu Đồ Doanh Thu Theo Tháng</h4>
    <div class="card mb-4">
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
    <h4 class="mb-3">Top 5 Sản Phẩm Bán Chạy</h4>
    <table class="table table-bordered">
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