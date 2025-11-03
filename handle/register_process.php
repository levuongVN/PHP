<?php
require_once '../functions/auth.php';
require_once '../functions/dbConnect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $errors = [];
    $old = ['full_name' => $full_name, 'username' => $username, 'email' => $email];

    // Validation logic
    // 1. Validate full_name
    if (empty($full_name)) {
        $errors['full_name'] = "Họ và tên không được để trống";
    } elseif (strlen($full_name) < 2) {
        $errors['full_name'] = "Họ và tên phải có ít nhất 2 ký tự";
    } elseif (!preg_match("/^[a-zA-ZÀ-ỹ\s]+$/u", $full_name)) {
        $errors['full_name'] = "Họ và tên chỉ được chứa chữ cái và khoảng trắng";
    }

    // 2. Validate username
    if (empty($username)) {
        $errors['username'] = "Tên đăng nhập không được để trống";
    } elseif (strlen($username) < 3) {
        $errors['username'] = "Tên đăng nhập phải có ít nhất 3 ký tự";
    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        $errors['username'] = "Tên đăng nhập chỉ được chứa chữ cái, số và dấu gạch dưới";
    }

    // 3. Validate email
    if (empty($email)) {
        $errors['email'] = "Email không được để trống";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email không hợp lệ";
    }

    // 4. Validate password
    if (empty($password)) {
        $errors['password'] = "Mật khẩu không được để trống";
    } elseif (strlen($password) < 6) {
        $errors['password'] = "Mật khẩu phải có ít nhất 6 ký tự";
    } elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
        $errors['password'] = "Mật khẩu phải chứa ít nhất 1 chữ hoa và 1 số";
    }

    // 5. Validate confirm_password
    if (empty($confirm_password)) {
        $errors['confirm_password'] = "Xác nhận mật khẩu không được để trống";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Mật khẩu xác nhận không khớp";
    }

    if (empty($errors)) {
        // Thông báo thành công

        $pdo = getDBConnection();
        $result = register($pdo, $username, $email, $password, $full_name);
        $_SESSION['success'] = "Đăng ký thành công! Vui lòng đăng nhập.";
        header('Location: ../login.php');
        exit();
    } else {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $old;
        header('Location: ../views/register/register.php');
        exit();
    }
} else {
    // Nếu không phải POST request, redirect về trang đăng ký
    header('Location: ../views/register/register.php');
    exit();
}