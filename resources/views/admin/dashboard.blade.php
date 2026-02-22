<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
</head>
<body>
    <h1>Chào {{ auth()->user()->ho_ten }}</h1>
    <p>Vai trò: {{ auth()->user()->vaiTro->ten_vai_tro }}</p>

    <hr>

    <ul>
        <li><a href="#">Quản lý sự kiện</a></li>
        <li><a href="#">Quản lý sinh viên</a></li>
        <li><a href="#">Thống kê</a></li>
    </ul>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Đăng xuất</button>
    </form>
</body>
</html>