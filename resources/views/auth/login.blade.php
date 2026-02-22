<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập</title>
</head>
<body>

<h2>Đăng nhập hệ thống</h2>

@if(session('error'))
    <p style="color:red">{{ session('error') }}</p>
@endif

<form method="POST" action="/login">
    @csrf
    <input type="email" name="email" placeholder="Email" required>
    <br><br>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <br><br>
    <button type="submit">Đăng nhập</button>
</form>

</body>
</html>