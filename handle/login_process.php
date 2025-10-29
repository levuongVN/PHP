<?php
// Bật hiển thị lỗi để debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once'../functions/dbConnect.php';
require_once '../functions/auth.php'; // Điều chỉnh đường dẫn cho phù hợp

// Lấy dữ liệu từ form
$username = $_POST['username'];
$password = $_POST['password'];

// Validate dữ liệu
if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Vui lòng nhập đầy đủ tên đăng nhập và mật khẩu.';
    header(header: 'Location: ../Index.php');
    exit();
}

$pdo = getDBConnection();
// Kiểm tra thông tin đăng nhập bằng hàm authenticate từ auth.php
$user = authenticateUser($pdo,$username, $password);

if ($user) {
    // Đăng nhập thành công
    // Lưu thông tin user vào session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['login_time'] = date('Y-m-d H:i:s');

    // Chuyển hướng đến trang chủ
    header('Location: ../views/Index.php');
    exit();
} else {
    // Đăng nhập thất bại
    error_log("Login failed for user: $username");
    $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không chính xác.';
    header('Location: ../Index.php');
    exit();
}
?>