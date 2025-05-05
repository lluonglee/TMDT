@extends('admin_layout')
@section('admin_content')
<div class="row">
    <div class="col-lg-12">
        <section class="panel">
            <header class="panel-heading">Danh sách đơn hàng</header>
            <div class="panel-body">
                @if(Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif

                <!-- Form lọc trạng thái -->
                <form method="GET" action="{{ url('/admin/orders') }}" class="form-inline mb-3">
                    <label for="status" class="mr-2">Lọc theo trạng thái:</label>
                    <select name="status" id="status" class="form-control mr-2">
                        <option value="">Tất cả</option>
                        <option value="Đang xử lý" {{ request('status') == 'Đang xử lý' ? 'selected' : '' }}>Đang xử lý
                        </option>
                        <option value="Hoàn thành" {{ request('status') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành
                        </option>
                        <option value="Đã hủy" {{ request('status') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Tên khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Phí Ship</th>
                            <th>Hình thức thanh toán</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($all_orders as $order)
                        <tr>
                            <td>{{ $order->order_id }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ number_format($order->order_total, 0, ',', '.') }} VNĐ</td>
                            <td>{{ number_format($order->shipping_fee, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $order->payment_method }}</td>
                            <td>
                                @if($order->order_status == 'Đang xử lý')
                                <span class="text-warning">{{ $order->order_status }}</span>
                                @elseif($order->order_status == 'Hoàn thành')
                                <span class="text-success">{{ $order->order_status }}</span>
                                @else
                                <span class="text-danger">{{ $order->order_status }}</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a style="font-size: 25px;" href="{{ url('/view-order/'.$order->order_id) }}"
                                    class="active">
                                    <i class="fa fa-eye text-primary"></i>
                                </a>
                                <a style="font-size: 25px;" href="{{ url('/delete-order/'.$order->order_id) }}"
                                    onclick="return confirm('Bạn có chắc muốn xóa đơn hàng này?');" class="active">
                                    <i class="fa fa-trash text-danger"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Không có đơn hàng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>

<style>
    .table th,
    .table td {
        color: #000 !important;
    }
</style>
@endsection