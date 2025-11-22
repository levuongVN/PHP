<?php
// functions/transaction.php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'dbConnect.php';

/**
 * Lấy danh mục theo loại (income/expense)
 */
function transaction_getCategories(mysqli $conn, int $userId, string $type): array {
    // Chỉ chấp nhận 2 loại hợp lệ
    if (!in_array($type, ['income', 'expense'])) {
        return [];
    }

    $sql = "SELECT id, name
            FROM categories
            WHERE type = ? AND (user_id = ? OR user_id IS NULL)
            ORDER BY name";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, "si", $type, $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    mysqli_stmt_close($stmt);

    return $rows;
}

/**
 * Thêm giao dịch (thu hoặc chi)
 */
function transaction_add(
    mysqli $conn,
    int $userId,
    int $categoryId,
    $amount,
    string $transactionDate,
    ?string $description,
    string $type
): array {
    // ===== Validate =====
    if (!in_array($type, ['income', 'expense'])) {
        return ['success' => false, 'message' => 'Loại giao dịch không hợp lệ.'];
    }

    if (empty($transactionDate)) {
        return ['success' => false, 'message' => 'Vui lòng chọn ngày giao dịch.'];
    }

    if (!is_numeric($amount) || (float)$amount <= 0) {
        return ['success' => false, 'message' => 'Số tiền không hợp lệ.'];
    }

    // ===== Kiểm tra danh mục hợp lệ =====
    $chkSql = "SELECT COUNT(*) AS c
            FROM categories
            WHERE id = ? AND type = ? AND (user_id = ? OR user_id IS NULL)";
    $chkStmt = mysqli_prepare($conn, $chkSql);
    if (!$chkStmt) {
        return ['success' => false, 'message' => 'Không thể kiểm tra danh mục.'];
    }

    mysqli_stmt_bind_param($chkStmt, "isi", $categoryId, $type, $userId);
    mysqli_stmt_execute($chkStmt);
    $chkRes = mysqli_stmt_get_result($chkStmt);
    $row = $chkRes ? mysqli_fetch_assoc($chkRes) : null;
    $okCat = $row && (int)$row['c'] > 0;
    mysqli_stmt_close($chkStmt);

    if (!$okCat) {
        return ['success' => false, 'message' => 'Danh mục không hợp lệ hoặc không thuộc người dùng.'];
    }

    // ===== Thêm mới =====
    $sql = "INSERT INTO transactions (user_id, category_id, amount, type, description, transaction_date)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return ['success' => false, 'message' => 'Không thể chuẩn bị truy vấn: ' . mysqli_error($conn)];
    }

    $amt  = (float)$amount;
    $desc = (!empty(trim($description))) ? trim($description) : null;

    // CHUỖI KIỂU phải đúng thứ tự & KHÔNG dư khoảng trắng
    mysqli_stmt_bind_param($stmt, "iidsss", $userId, $categoryId, $amt, $type, $desc, $transactionDate);

    $ok  = mysqli_stmt_execute($stmt);
    $err = $ok ? '' : mysqli_error($conn);
    mysqli_stmt_close($stmt);

    return $ok
        ? ['success' => true, 'message' => 'Thêm giao dịch thành công!']
        : ['success' => false, 'message' => 'Lỗi khi lưu giao dịch: ' . $err];
}
/**
 * Lấy tất cả giao dịch của người dùng
 */
function transaction_get_all(mysqli $conn, int $userId): array {
    $sql = "SELECT 
                t.id,
                t.category_id,   
                t.transaction_date,
                t.amount,
                t.description,
                t.type,
                c.name AS category_name
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = ?
            ORDER BY t.transaction_date DESC";

    $stmt = mysqli_prepare($conn, $sql);
    if (!$stmt) {
        return [];
    }

    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $rows = $res ? mysqli_fetch_all($res, MYSQLI_ASSOC) : [];
    mysqli_stmt_close($stmt);

    return $rows;
}
?>