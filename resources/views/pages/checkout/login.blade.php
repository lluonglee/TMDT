<div class="container">
    <h2>Đăng Nhập</h2>
    @if(Session::has('error'))
    <p class="alert alert-danger">{{ Session::get('error') }}</p>
    @endif
    <form action="{{ url('/customer-login') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email_account" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mật khẩu:</label>
            <input type="password" name="password_account" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Đăng Nhập</button>
        <p>Chưa có tài khoản? <a href="{{ url('/customer/register') }}">Đăng ký ngay</a></p>
    </form>
</div>