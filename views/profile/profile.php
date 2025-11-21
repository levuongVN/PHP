<?php
session_start();
require_once(__DIR__ . "/../../handle/profile_process.php");
$dataProfile = getProfileById();
$theme = $_SESSION['theme'] ?? '';
$backUrl = $_SESSION['last_page'] ?? '';
$username = $_SESSION['oldName'] ?? $dataProfile['username'];
$full_name = $_SESSION['oldFullName'] ?? $dataProfile['full_name'];
$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];
$errUsername = $_SESSION['errUsername'] ?? '';
$errFullName = $_SESSION['errFullName'] ?? '';
$errCurrentPassword = $_SESSION['errCurrentPassword'] ?? '';
$errNewPassword = $_SESSION['errNewPassword'] ?? '';
// Xóa session lỗi sau khi lấy
unset($_SESSION['oldName'], $_SESSION['oldFullName'], $_SESSION['errUsername'], $_SESSION['errFullName'], $_SESSION['errCurrentPassword'], $_SESSION['errNewPassword']);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ Sơ Người Dùng</title>
    <link rel="stylesheet" href="../../css/profile.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: <?= $theme ?>">

    <div class="container">
        <div class="profile-card">
            <div class="text-start mb-3">
                <a href="<?= htmlspecialchars($backUrl) ?>" class="btn">&larr; Trở lại</a>
            </div>
            <div class="text-center">
                <h3 class="fw-bold">Hồ Sơ Người Dùng</h3>
                <p class="text-muted">Cập nhật thông tin tài khoản</p>
            </div>

            <hr class="my-4">
            <form method="post" action="../../handle/profile_process.php">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên tài khoản</label>
                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($username) ?>">
                    <?php if ($errUsername): ?>
                        <div class="text-danger small mt-1"><?= htmlspecialchars($errUsername) ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên đầy đủ</label>
                    <input type="text" class="form-control" name="full_name"
                        value="<?= htmlspecialchars($full_name) ?>">
                    <?php if ($errFullName): ?>
                        <div class="text-danger small mt-1"><?= htmlspecialchars($errFullName) ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" name="email" disabled
                        value="<?= htmlspecialchars($dataProfile['email']) ?>">
                </div>
                <div class="mb-3">
                    <button class="btn btn-outline-secondary w-100" type="button" data-bs-toggle="collapse"
                        data-bs-target="#passwordFields">
                        Đổi mật khẩu
                    </button>
                </div>

                <div class="collapse" id="passwordFields">
                    <div class="mb-3 mt-3">
                        <label class="form-label fw-semibold">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" name="current_password"
                            placeholder="Nhập mật khẩu hiện tại">
                        <?php if ($errCurrentPassword): ?>
                            <div class="text-danger small mt-1"><?= htmlspecialchars($errCurrentPassword) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu mới</label>
                        <input type="password" class="form-control" name="new_password" placeholder="Nhập mật khẩu mới">
                        <?php if ($errNewPassword): ?>
                            <div class="text-danger small mt-1"><?= htmlspecialchars($errNewPassword) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu mới</label>
                        <input type="password" class="form-control" name="confirm_password"
                            placeholder="Nhập lại mật khẩu mới">
                    </div>
                </div>


                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <label class="form-label toggle-label">Chế độ giao diện</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" <?= $theme =="#495057" ? 'checked' : '' ?> id="darkModeToggle" name="dataTheme">
                    </div>
                </div>
                <input type="hidden" name="dark_mode" id="darkModeInput" value="<?= $theme ? '1' : '0' ?>">
                <button class="btn btn-primary w-100 mt-3 py-2 fw-semibold">Lưu thay đổi</button>
            </form>

        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const darkModeToggle = document.getElementById("darkModeToggle");
        const profileCard = document.querySelector(".profile-card");
        const darkModeInput = document.getElementById("darkModeInput");

        darkModeToggle.addEventListener("change", function () {
            const isDark = this.checked;
            document.body.style.background = isDark ? "#495057" : "linear-gradient(135deg, #e3f2fd, #f8f9fa)";
            darkModeInput.value = isDark ? '1' : '0';
        });
        <?php if ($errCurrentPassword || $errNewPassword): ?>
            const passwordCollapse = new bootstrap.Collapse(document.getElementById('passwordFields'), {
                toggle: true
            });
        <?php endif; ?>
    </script>
</body>

</html>