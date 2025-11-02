<?php
function getTopSpendingCategoriesAllTime($conn, $user_id, $limit = 4) {
    $sql = "SELECT 
                c.id,
                c.name as category_name,
                c.icon,
                COALESCE(SUM(t.amount), 0) as total_spent,
                COALESCE((
                    SELECT b.amount 
                    FROM budgets b 
                    WHERE b.category_id = c.id 
                    AND b.user_id = ? 
                    ORDER BY b.month DESC 
                    LIMIT 1
                ), 0) as total_budget
            FROM categories c
            LEFT JOIN transactions t ON c.id = t.category_id AND t.user_id = ? AND t.type = 'expense'
            WHERE c.user_id = ? AND c.type = 'expense'
            GROUP BY c.id, c.name, c.icon
            ORDER BY total_spent DESC
            LIMIT ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiii", $user_id, $user_id, $user_id, $limit);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row;
    }
    
    mysqli_stmt_close($stmt);
    return $categories;
}
?>