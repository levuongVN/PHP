<?php
function isLoggedIn()
{
    // Khởi tạo session nếu chưa có
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Kiểm tra xem user đã đăng nhập chưa
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['username'])) {
        // Nếu chưa đăng nhập, set thông báo lỗi và chuyển hướng
        $_SESSION['errorLogin'] = 'Bạn cần đăng nhập để truy cập trang này!';
        header('Location: ../../Index.php ');
        exit();
    }
}

function authenticateUser($conn, $username, $password) {
    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) return false;
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if ($password === $user['password']) { // Nên dùng password_verify nếu có mã hóa
            mysqli_stmt_close($stmt);
            return $user;
        }
    }
    if ($stmt) mysqli_stmt_close($stmt);
    return false;
}

function logout($redirectPath = '../Index.php') {
    // Khởi tạo session nếu chưa có
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Hủy tất cả session
    session_unset();
    session_destroy();
    
    // Khởi tạo session mới để lưu thông báo
    session_start();
    $_SESSION['success'] = 'Đăng xuất thành công!';
    
    // Chuyển hướng về trang đăng nhập
    header('Location: ' . $redirectPath);
    exit();
}

/**
 * Đăng ký người dùng mới
 */
function registerUser($conn, $username,$email, $password, $full_name)
{
    // Kiểm tra trùng username
    $checkUserSql = "SELECT id FROM users WHERE username = ?";
    $checkUserStmt = mysqli_prepare($conn, $checkUserSql);
    mysqli_stmt_bind_param($checkUserStmt, "s", $username);
    mysqli_stmt_execute($checkUserStmt);
    $resultUser = mysqli_stmt_get_result($checkUserStmt);

    if ($resultUser && mysqli_num_rows($resultUser) > 0) {
        mysqli_stmt_close($checkUserStmt);
        return ['success' => false, 'message' => 'Tên đăng nhập đã tồn tại!'];
    }
    mysqli_stmt_close($checkUserStmt);

    // Kiểm tra trùng email
    $checkEmailSql = "SELECT id FROM users WHERE email = ?";
    $checkEmailStmt = mysqli_prepare($conn, $checkEmailSql);
    mysqli_stmt_bind_param($checkEmailStmt, "s", $email);
    mysqli_stmt_execute($checkEmailStmt);
    $resultEmail = mysqli_stmt_get_result($checkEmailStmt);

    if ($resultEmail && mysqli_num_rows($resultEmail) > 0) {
        mysqli_stmt_close($checkEmailStmt);
        return ['success' => false, 'message' => 'Email đã tồn tại!'];
    }
    mysqli_stmt_close($checkEmailStmt);

    // Thêm người dùng mới
    $insertSql = "INSERT INTO users (username, password, email, full_name) VALUES (?, ?, ?, ?)";
    $insertStmt = mysqli_prepare($conn, $insertSql);
    if (!$insertStmt) {
        return ['success' => false, 'message' => 'Không thể chuẩn bị truy vấn!'];
    }

    mysqli_stmt_bind_param($insertStmt, "ssss", $username, $password, $email, $full_name);
    $result = mysqli_stmt_execute($insertStmt);
    mysqli_stmt_close($insertStmt);

    if ($result) {
        return ['success' => true, 'message' => 'Đăng ký thành công!'];
    } else {
        return ['success' => false, 'message' => 'Lỗi khi đăng ký: ' . mysqli_error($conn)];
    }
}
?>
