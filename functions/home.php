<?php
/**
 * Lấy tổng thu nhập của user
 */
function getTotalIncome($conn, $user_id) {
    $sql = "SELECT COALESCE(SUM(amount), 0) as total_income 
            FROM transactions 
            WHERE user_id = ? 
            AND type = 'income'";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        return (float)$row['total_income'];
    }
    
    return 0;
}

/**
 * Lấy tổng chi tiêu của user
 */
function getTotalExpense($conn, $user_id) {
    $sql = "SELECT COALESCE(SUM(amount), 0) as total_expense 
            FROM transactions 
            WHERE user_id = ? 
            AND type = 'expense'";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        return (float)$row['total_expense'];
    }
    
    return 0;
}

/**
 * Lấy giao dịch gần đây
 */
function getRecentTransactions($conn, $user_id, $limit = 5) {
    $sql = "SELECT t.*, c.name as category_name, c.color, c.icon 
            FROM transactions t 
            JOIN categories c ON t.category_id = c.id 
            WHERE t.user_id = ? 
            ORDER BY t.transaction_date DESC, t.created_at DESC 
            LIMIT ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $transactions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $transactions[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $transactions;
    }
    
    return [];
}
?>