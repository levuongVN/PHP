<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../functions/dbConnect.php");
require_once(__DIR__ . "/../functions/profile.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    updateProfile();
}
function updateProfile()
{
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    $profile = getInformation($conn, $user_id);
    $user_name = $_POST['username'] ?? '';
    $dataTheme = $_POST['dataTheme'] ??'';
    $user_full_name = $_POST['full_name'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $has_err = false;
    // Validate tên đầy đủ
    if (empty($user_full_name)) {
        $_SESSION['errFullName'] = 'Tên không được để trống!';
        $has_err = true;
    }

    if (empty($user_name)) {
        $_SESSION['errUsername'] = 'Tên đăng nhập không được để trống';
        $has_err = true;
    }

    // Password errors
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if ($profile['password'] != $current_password) {
            $_SESSION['errCurrentPassword'] = "Mật khẩu hiện tại không đúng.";
            $has_err = true;
        }
        if ($new_password !== $confirm_password) {
            $_SESSION['errNewPassword'] = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
            $has_err = true;
        }

        if (strlen($new_password) < 6 && !empty($new_password)) {
            $_SESSION['errNewPassword'] = "Mật khẩu mới phải ít nhất 6 ký tự.";
            $has_err = true;
        }
    }

    if ($has_err) {
        $_SESSION["oldName"] = $user_name;
        $_SESSION["oldFullName"] = $user_full_name;
        Header("Location:../views/profile/profile.php");
        exit;
    } else {
        if($dataTheme == "on"){
            $_SESSION['theme'] = '#495057';
        }else{
            $_SESSION['theme'] = 'linear-gradient(135deg, #e3f2fd, #f8f9fa)';
        }
        updateInformation(
            $conn,
            $user_id,
            $user_name,
            $new_password ? $new_password : $profile['password'],
            $user_full_name
        );
        Header("Location:../views/profile/profile.php");
        exit;
    }
}

// Lấy profile
function getProfileById()
{
    $user_id = $_SESSION['user_id'];
    $conn = getDBConnection();
    return getInformation($conn, $user_id);
}
?>