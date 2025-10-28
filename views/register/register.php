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
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header bg-success text-white text-center">
                        <h3 class="mb-0">Đăng ký tài khoản</h3>
                    </div>
                    <div class="card-body p-4">
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

                            <button type="submit" class="btn btn-success w-100">Đăng ký</button>
                        </form>

                        <div class="text-center mt-3">
                            <p class="mb-0">Đã có tài khoản? <a href="../../Index.php">Đăng nhập tại đây</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>