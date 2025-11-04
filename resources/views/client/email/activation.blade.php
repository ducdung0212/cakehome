<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kích hoạt tài khoản</title>
</head>
<body>
    <h1>Kích hoạt tài khoản</h1>
    <p>Xin chào {{ $user->name }},</p>
    <p>Vui lòng nhấp vào liên kết bên dưới để kích hoạt tài khoản của bạn:</p>
    <a href="{{ route('activate.account', $token) }}" style="padding:10px 5px; background-color:green; color:white; text-decoration:none;">Kích hoạt tài khoản</a>
</body>
</html>