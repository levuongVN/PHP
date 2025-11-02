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
/**
 * Lấy ngân sách theo danh mục cho tháng hiện tại
 */
function getCategoryBudgets($conn, $user_id, $month) {
    try {
        
        $sql = "
                SELECT 
                c.name as category_name,
                c.color,
                c.icon,
                COALESCE(b.amount, 0) as budget_amount,
                COALESCE(SUM(t.amount), 0) as spent_amount
                FROM categories c
                LEFT JOIN budgets b ON c.id = b.category_id AND b.user_id = ? AND DATE_FORMAT(b.month, '%Y-%m') = ?
                LEFT JOIN transactions t ON c.id = t.category_id AND t.user_id = ?
                AND t.type = 'expense' 
                AND DATE_FORMAT(t.transaction_date, '%Y-%m') = ?
                WHERE (c.user_id = ? OR c.user_id IS NULL) AND c.type = 'expense'
                GROUP BY c.id, c.name, c.color, c.icon, b.amount;
        ";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isisi", $user_id, $month, $user_id, $month, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $budgets = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $budgets[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $budgets;
    } catch (Exception $e) {
        error_log("Error getting category budgets: " . $e->getMessage());
        return [];
    }
}
function getTotalIncomeByMonth($conn, $user_id, $month) {
    try {
    $sql = "SELECT COALESCE(SUM(amount), 0) as total_income 
    FROM transactions 
    WHERE user_id = ?
    AND type = 'income'
    AND DATE_FORMAT(transaction_date, '%Y-%m') = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        return (float)$row['total_income'];
    }}catch (Exception $e) {
        error_log(''. $e->getMessage());
    }
    
    return 0;
}

function getTotalExpenseByMonth($conn, $user_id, $month) {
    $current_month = date(format: 'Y-m');
    $sql = "SELECT COALESCE(SUM(amount), 0) as total_expense 
            FROM transactions 
            WHERE user_id = ? 
            AND type = 'expense'
            AND DATE_FORMAT(transaction_date, '%Y-%m') = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        return (float)$row['total_expense'];
    }
    
    return 0;
}

function getIncomeChangePercent($conn, $user_id) {
    $current_month = date('Y-m');
    $previous_month = date('Y-m', strtotime('-1 month'));
    
    $current_income = getTotalIncomeByMonth($conn, $user_id, $current_month);
    $previous_income = getTotalIncomeByMonth($conn, $user_id, $previous_month);
    
    if ($previous_income == 0) {
        return $current_income > 0 ? 100 : 0;
    }
    
    $change_percent = (($current_income - $previous_income) / $previous_income) * 100;
    return round($change_percent, 1);
}

function getBalanceUntilMonth($conn, $user_id, $year_month) {
    $sql = "SELECT 
        COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as total_income,
        COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as total_expense
    FROM transactions 
    WHERE user_id = ? AND DATE_FORMAT(transaction_date, '%Y-%m') <= ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "is", $user_id, $year_month);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        
        mysqli_stmt_close($stmt);
        return $row['total_income'] - $row['total_expense'];
    }
    
    return 0;
}
?>