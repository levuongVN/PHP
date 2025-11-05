<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../functions/dbConnect.php';
require_once '../functions/transaction.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../Index.php');
    exit();
}

$conn   = getDbConnection();
$userId = (int)$_SESSION['user_id'];

// Lấy dữ liệu từ form
$type        = $_POST['type'] ?? 'income';
$categoryId  = (int)($_POST['category_id'] ?? 0);
$amount      = $_POST['amount'] ?? 0;
$date        = $_POST['transaction_date'] ?? '';
$desc        = $_POST['description'] ?? '';
echo "Type: $type, Category ID: $categoryId, Amount: $amount, Date: $date, Desc: $desc"; // Debug line

// Gọi hàm thêm giao dịch
$result = transaction_add($conn, $userId, $categoryId, $amount, $date, $desc, $type);

// Thiết lập thông báo và chuyển hướng lại đúng vị trí file
if ($result['success']) {
    echo "Success: " . $result['message']; // Debug line
    $_SESSION['success'] = $result['message'];
} else {
    echo "Error: " . $result['message']; // Debug line
    $_SESSION['error'] = $result['message'];
}

// header("Location: ../views/transaction/transaction_create.php?type=$type");
exit();
