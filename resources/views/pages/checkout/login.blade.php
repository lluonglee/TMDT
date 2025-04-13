<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Thiết lập nền cho body */
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

        /* Căn giữa form */
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            text-align: center;
        }

        /* Tiêu đề */
        h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }

        /* Các trường input */
        input.form-control {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Nút submit */
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

        /* Alert Error */
        .alert.alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        /* Liên kết đăng ký */
        p a {
            color: #007bff;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
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


</body>

</html>
<!-- <div class="container">
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
</div> -->