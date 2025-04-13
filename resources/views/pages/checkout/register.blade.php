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

        /* Nút đăng ký */
        button.btn-success {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            ;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button.btn-success:hover {
            background-color: #0056b3;
        }

        /* Liên kết đăng nhập */
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
        <h2>Đăng Ký</h2>
        <form action="{{ url('/customer-register') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Họ tên:</label>
                <input type="text" name="customer_name" class="form-control" required>
                @error('customer_name')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="customer_email" class="form-control" required>
                @error('customer_email')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Mật khẩu:</label>
                <input type="password" name="customer_password" class="form-control" required>
                @error('customer_password')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Xác nhận mật khẩu:</label>
                <input type="password" name="customer_password_confirmation" class="form-control" required>
                @error('customer_password_confirmation')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="customer_phone" class="form-control" required>
                @error('customer_phone')
                <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Đăng Ký</button>
            <p>Đã có tài khoản? <a href="{{ url('/customer/login') }}">Đăng nhập ngay</a></p>
        </form>
    </div>


</body>

</html>
<!-- <div class="container">
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
</div> -->