<div class="container">
    <h2>Đăng Ký</h2>
    <form action="{{ url('/customer-register') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Họ tên:</label>
            <input type="text" name="customer_name" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="customer_email" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mật khẩu:</label>
            <input type="password" name="customer_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" name="customer_phone" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Đăng Ký</button>
        <p>Đã có tài khoản? <a href="{{ url('/customer/login') }}">Đăng nhập ngay</a></p>
    </form>
</div>