@extends('admin_layout')
@section('admin_content')

<div class="container">
    <h2 class="my-3">Thống Kê Đơn Hàng</h2>

    {{-- Form lọc theo khoảng thời gian --}}
    <form action="{{ url('/statistics') }}" method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-auto">
            <label for="start_date" class="form-label">Từ ngày</label>
            <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ request('start_date') ?? \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d') }}" required>
        </div>
        <div class="col-auto">
            <label for="end_date" class="form-label">Đến ngày</label>
            <input type="date" name="end_date" id="end_date" class="form-control"
                value="{{ request('end_date') ?? \Carbon\Carbon::now()->endOfMonth()->format('Y-m-d') }}" required>
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