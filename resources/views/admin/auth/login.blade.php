<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng Nhập Admin - CakeHome</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://unpkg.com/toastr@2.1.4/build/toastr.min.css">

    <link rel="stylesheet" href="{{ asset('assets/admin/css/login.css') }}">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('images/logo/favicon.png') }}" alt="CakeHome Logo">
                <h3>Admin Panel</h3>
                <p>Chào mừng bạn quay trở lại</p>
            </div>

            <div class="login-body">
                <form action="{{ route('admin.login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" class="form-control" id="email" name="email" required autofocus>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label text-secondary" style="font-size: 0.9rem;" for="remember">
                                Ghi nhớ
                            </label>
                        </div>
                        <a href="/admin/forgot-password" class="text-decoration-none text-primary-custom"
                            style="font-size: 0.9rem;">
                            Quên mật khẩu?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-login">
                        Đăng Nhập
                    </button>
                </form>
            </div>
        </div>

        <div class="back-to-site">
            <a href="/">
                <i class="bi bi-arrow-left"></i>
                Quay về trang chủ
            </a>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://unpkg.com/toastr@2.1.4/build/toastr.min.js"></script>
    @include('components.toast')
</body>

</html>
