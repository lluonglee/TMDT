<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .container {
        background-color: white;
        padding: 40px;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        width: 350px;
        text-align: center;
    }

    h2 {
        color: #333;
        font-size: 24px;
        margin-bottom: 20px;
    }

    input.form-control {
        width: 100%;
        padding: 12px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    button.btn-primary {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        color: white;
        font-size: 16px;
        cursor: pointer;
    }

    button.btn-primary:hover {
        background-color: #0056b3;
    }

    .alert.alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    p a {
        color: #007bff;
        text-decoration: none;
    }

    p a:hover {
        text-decoration: underline;
    }

    label {
        display: block;
        text-align: left;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Đặt Lại Mật Khẩu</h2>
        @if(Session::has('error'))
        <p class="alert alert-danger">{{ Session::get('error') }}</p>
        @endif
        <form action="{{ url('/reset-password') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ request()->query('token') }}">
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" class="form-control" value="{{ request()->query('email') }}" required
                    readonly>
            </div>
            <div class="form-group">
                <label>Mật Khẩu Mới:</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Xác Nhận Mật Khẩu:</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Cập Nhật Mật Khẩu</button>
            <p>Quay lại <a href="{{ url('/customer/login') }}">Đăng nhập</a></p>
        </form>
    </div>
</body>

</html>