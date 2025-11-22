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

// Xác định hành động: create / update / delete
$action = $_POST['action'] ?? 'create';
$id     = isset($_POST['id']) ? (int)$_POST['id'] : 0;

// ================== XOÁ GIAO DỊCH ==================
if ($action === 'delete') {
    if ($id <= 0) {
        $_SESSION['error'] = 'Giao dịch không hợp lệ.';
        header("Location: ../views/transaction/transaction_index.php");
        exit();
    }

    $sql  = "DELETE FROM transactions WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $_SESSION['error'] = 'Không thể xoá giao dịch: ' . mysqli_error($conn);
        header("Location: ../views/transaction/transaction_index.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "ii", $id, $userId);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    if ($ok) {
        $_SESSION['success'] = 'Xoá giao dịch thành công.';
    } else {
        $_SESSION['error'] = 'Lỗi khi xoá giao dịch.';
    }

    mysqli_close($conn);
    header("Location: ../views/transaction/transaction_index.php");
    exit();
}

// ================== CREATE / UPDATE ==================

// Lấy dữ liệu từ form
$type       = $_POST['type'] ?? 'income';
$categoryId = (int)($_POST['category_id'] ?? 0);

// Tiền: người dùng gõ "1.000.000" -> chuyển về 1000000
$amountRaw   = $_POST['amount'] ?? '0';
$amountClean = str_replace('.', '', $amountRaw);
$amount      = (float)$amountClean;

$date      = $_POST['transaction_date'] ?? '';
$desc      = $_POST['description'] ?? '';
$fromIndex = isset($_POST['from_index']) && $_POST['from_index'] == '1';

// --------- VALIDATE cơ bản ---------
$errors = [];

if (!in_array($type, ['income', 'expense'])) {
    $errors[] = 'Loại giao dịch không hợp lệ.';
}

if ($categoryId <= 0) {
    $errors[] = 'Vui lòng chọn danh mục.';
}

if ($amount <= 0) {
    $errors[] = 'Vui lòng nhập số tiền hợp lệ.';
}

if (empty($date)) {
    $errors[] = 'Vui lòng chọn ngày giao dịch.';
}

// Nếu có lỗi thì trả về trang phù hợp
if (!empty($errors)) {
    $_SESSION['error'] = implode(' ', $errors);

    if ($action === 'update' && $id > 0) {
        // Sửa bằng modal nên quay lại danh sách
        header("Location: ../views/transaction/transaction_index.php");
    } else {
        if ($fromIndex) {
            header("Location: ../views/transaction/transaction_index.php");
        } else {
            header("Location: ../views/transaction/transaction_create.php?type=" . $type);
        }
    }
    exit();
}

// ================== XỬ LÝ CSDL ==================
if ($action === 'update' && $id > 0) {
    // --- SỬA GIAO DỊCH ---
    $sql = "UPDATE transactions
            SET category_id = ?, amount = ?, transaction_date = ?, description = ?, type = ?
            WHERE id = ? AND user_id = ?";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        $_SESSION['error'] = 'Không thể cập nhật giao dịch: ' . mysqli_error($conn);
        header("Location: ../views/transaction/transaction_index.php");
        exit();
    }

    mysqli_stmt_bind_param(
        $stmt,
        "idsssii",
        $categoryId,
        $amount,
        $date,
        $desc,
        $type,
        $id,
        $userId
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = 'Cập nhật giao dịch thành công.';
    } else {
        $_SESSION['error'] = 'Lỗi khi cập nhật giao dịch.';
    }

    mysqli_stmt_close($stmt);

    // Sửa xong luôn quay lại danh sách
    header("Location: ../views/transaction/transaction_index.php");
    exit();
} else {
    // --- THÊM GIAO DỊCH MỚI ---
    // Gọi hàm helper có sẵn
    $result = transaction_add($conn, $userId, $categoryId, $amount, $date, $desc, $type);

    // Thiết lập thông báo
    if (!empty($result['success'])) {
        $_SESSION['success'] = $result['message'];
    } else {
        $_SESSION['error'] = $result['message'];
    }

    // Nếu gửi từ trang index (modal) thì quay về index, không về trang create
    if ($fromIndex) {
        header("Location: ../views/transaction/transaction_index.php");
    } else {
        header("Location: ../views/transaction/transaction_create.php?type=" . $type);
    }
    exit();
}
