<?php
session_start();
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Quản lý chi tiêu</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-container">
                    <div class="login-header">
                        <h2 class="mb-0"><i class="fas fa-wallet me-2"></i>QUẢN LÝ CHI TIÊU</h2>
                        <p class="mb-0 mt-2 opacity-75">Đăng nhập vào tài khoản của bạn</p>
                    </div>
                    <div class="login-form">
                        <?php if(isset($_SESSION['errorLogin'])) : ?>
                        <div class="alert alert-danger mt-0" role="alert">
                            <?= $_SESSION['errorLogin'] ?>
                        </div>
                        <?php endif; ?>
                        <form action="./handle/login_process.php" method="POST" onsubmit="return validateLoginForm()">
                            <!-- Username/Email Field -->
                            <div class="mb-4">
                                <label for="username" class="form-label fw-semibold">Tên đăng nhập hoặc Email:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" id="username" name="username" class="form-control"
                                        placeholder="Nhập tên đăng nhập hoặc email">
                                </div>
                                <div id="usernameError" class="error"></div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Mật khẩu:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" id="password" name="password" class="form-control"
                                        placeholder="Nhập mật khẩu">
                                </div>
                                <div id="passwordError" class="error"></div>
                            </div>

                            <!-- Error Message -->
                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <div><?php echo $error; ?></div>
                                </div>
                            <?php endif; ?>

                            <!-- Submit Button -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-login btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>ĐĂNG NHẬP
                                </button>
                            </div>
                        </form>

                        <!-- Register Link -->
                        <div class="text-center">
                            <p class="mb-2">Chưa có tài khoản?</p>
                            <a href="./views/register/register.php" class="btn btn-outline-primary">
                                <i class="fas fa-user-plus me-2"></i>Đăng ký tài khoản mới
                            </a>
                        </div>

                        <!-- Demo Account Info -->
                        <div class="mt-4 p-3 demo-info">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Tài khoản demo:</strong> demo / 123456
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle với Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function validateLoginForm() {
            let username = document.getElementById('username').value.trim();
            let password = document.getElementById('password').value.trim();
            let isValid = true;

            // Reset errors
            document.getElementById('usernameError').textContent = '';
            document.getElementById('passwordError').textContent = '';

            // Validate username
            if (username === '') {
                document.getElementById('usernameError').textContent = 'Vui lòng nhập tên đăng nhập hoặc email.';
                isValid = false;
            }

            // Validate password
            if (password === '') {
                document.getElementById('passwordError').textContent = 'Vui lòng nhập mật khẩu.';
                isValid = false;
            } else if (password.length < 6) {
                document.getElementById('passwordError').textContent = 'Mật khẩu phải có ít nhất 6 ký tự.';
                isValid = false;
            }

            return isValid;
        }

        // Real-time validation
        document.getElementById('username').addEventListener('blur', function () {
            const username = this.value.trim();
            if (username === '') {
                document.getElementById('usernameError').textContent = 'Vui lòng nhập tên đăng nhập hoặc email.';
            } else {
                document.getElementById('usernameError').textContent = '';
            }
        });

        document.getElementById('password').addEventListener('input', function () {
            const password = this.value.trim();
            if (password !== '' && password.length < 6) {
                document.getElementById('passwordError').textContent = 'Mật khẩu phải có ít nhất 6 ký tự.';
            } else {
                document.getElementById('passwordError').textContent = '';
            }
        });
    </script>
</body>

</html>