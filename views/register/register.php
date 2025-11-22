<?php
session_start();
$errors = [];
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

$old = [];
if (isset($_SESSION['old'])) {
    $old = $_SESSION['old'];
    unset($_SESSION['old']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Quản lý chi tiêu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Background mờ giống trang đăng nhập */
        body::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            filter: blur(6px) brightness(0.6);
            z-index: -2;
        }

        body::after {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.2), transparent 55%),
                        radial-gradient(circle at bottom right, rgba(255,255,255,0.15), transparent 55%);
            z-index: -1;
        }

        .register-wrapper {
            width: 100%;
            max-width: 480px;
            padding: 20px;
        }

        .card-register {
            background: rgba(255, 255, 255, 0.96);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
        }

        .card-header-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            text-align: center;
            padding: 24px 20px;
        }

        .card-header-register h3 {
            margin: 0;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-body-register {
            padding: 26px 26px 22px;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control {
            border-radius: 12px;
            border: 1px solid #e1e5ee;
            padding: 10px 12px;
            box-shadow: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, 0.3);
        }

        .btn-register {
            border-radius: 999px;
            padding: 10px 16px;
            font-weight: 600;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: #fff;
            box-shadow: 0 10px 22px rgba(102, 126, 234, 0.5);
            transition: all 0.2s ease;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 14px 28px rgba(102, 126, 234, 0.6);
        }

        .text-danger {
            font-size: 0.85rem;
            margin-top: 4px;
        }

        .login-link {
            color: #4c6fff;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 576px) {
            .register-wrapper {
                padding: 10px;
            }
            body::before {
                filter: blur(5px) brightness(0.7);
            }
        }
    </style>
</head>
<body>
    <div class="register-wrapper">
        <div class="card-register">
            <div class="card-header-register">
                <h3 class="mb-0">Đăng ký tài khoản</h3>
            </div>
            <div class="card-body-register">
                <form action="../../handle/register_process.php" method="POST">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Họ và tên:</label>
                        <input type="text" id="full_name" name="full_name" class="form-control" 
                               value="<?php echo isset($old['full_name']) ? htmlspecialchars($old['full_name']) : ''; ?>">
                        <?php if (isset($errors['full_name'])): ?>
                            <div class="text-danger"><?php echo $errors['full_name']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập:</label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?php echo isset($old['username']) ? htmlspecialchars($old['username']) : ''; ?>">
                        <?php if (isset($errors['username'])): ?>
                            <div class="text-danger"><?php echo $errors['username']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email" class="form-control" 
                               value="<?php echo isset($old['email']) ? htmlspecialchars($old['email']) : ''; ?>">
                        <?php if (isset($errors['email'])): ?>
                            <div class="text-danger"><?php echo $errors['email']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mật khẩu:</label>
                        <input type="password" id="password" name="password" class="form-control">
                        <?php if (isset($errors['password'])): ?>
                            <div class="text-danger"><?php echo $errors['password']; ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Xác nhận mật khẩu:</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control">
                        <?php if (isset($errors['confirm_password'])): ?>
                            <div class="text-danger"><?php echo $errors['confirm_password']; ?></div>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($errors['general'])): ?>
                        <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
                    <?php endif; ?>

                    <button type="submit" class="btn-register w-100">Đăng ký</button>
                </form>

                <div class="text-center mt-3">
                    <p class="mb-0 text-muted">
                        Đã có tài khoản?
                        <a href="../../Index.php" class="login-link">Đăng nhập tại đây</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
