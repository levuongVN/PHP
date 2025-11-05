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
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Background image với hiệu ứng blur */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1611&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(8px) brightness(0.8);
            transform: scale(1.1); /* Giúp blur không bị viền trắng */
            z-index: -2;
        }

        /* Lớp phủ màu để tăng độ tương phản */
        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(102, 126, 234, 0.3); /* Màu tím với độ trong suốt */
            z-index: -1;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeInUp 0.8s ease-out;
        }

        .login-header {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            color: white;
            padding: 35px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .login-header::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }

        .login-form {
            padding: 40px;
        }

        .form-control {
            border: 1px solid #e1e5ee;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
            transform: translateY(-2px);
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 14px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }

        .error {
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 5px;
        }

        .input-group-text {
            background-color: rgba(248, 249, 250, 0.8);
            border: 1px solid #e1e5ee;
            border-right: none;
            border-radius: 10px 0 0 10px;
        }

        .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: #667eea;
            background-color: #e9ecef;
        }

        .demo-info {
            background: rgba(248, 249, 250, 0.7);
            border-radius: 10px;
            border: 1px solid rgba(222, 226, 230, 0.5);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shine {
            0% {
                transform: rotate(45deg) translateX(-100%);
            }
            100% {
                transform: rotate(45deg) translateX(100%);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
            }
            
            .login-form {
                padding: 30px 25px;
            }
            
            body::before {
                filter: blur(5px) brightness(0.7);
            }
        }
    </style>
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
                        <form action="./handle/login_process.php" method="POST" onsubmit="return validateLoginForm()">
                            <!-- Username/Email Field -->
                            <div class="mb-4">
                                <label for="username" class="form-label fw-semibold">Tên đăng nhập hoặc Email:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" 
                                           id="username" 
                                           name="username" 
                                           class="form-control"
                                           placeholder="Nhập tên đăng nhập hoặc email">
                                </div>
                                <div id="usernameError" class="error"></div>
                            </div>

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Mật khẩu:</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" 
                                           id="password" 
                                           name="password" 
                                           class="form-control"
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
        document.getElementById('username').addEventListener('blur', function() {
            const username = this.value.trim();
            if (username === '') {
                document.getElementById('usernameError').textContent = 'Vui lòng nhập tên đăng nhập hoặc email.';
            } else {
                document.getElementById('usernameError').textContent = '';
            }
        });

        document.getElementById('password').addEventListener('input', function() {
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