<?php
function getTopSpendingCategoriesAllTime($conn, $user_id) {
    try {
        $sql = "
            SELECT 
                c.id,
                c.name as category_name,
                c.color,
                c.icon,
                COALESCE(SUM(t.amount), 0) as total_spent,
                COALESCE(SUM(b.amount), 0) as total_budget 
            FROM categories c
            LEFT JOIN transactions t ON c.id = t.category_id AND t.user_id = ? AND t.type = 'expense'
            LEFT JOIN budgets b ON c.id = b.category_id AND b.user_id = ?  
            WHERE c.user_id = ? AND c.type = 'expense'
            GROUP BY c.id, c.name, c.color, c.icon 
            ORDER BY total_spent DESC
            LIMIT 4
        ";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $categories;
    } catch (Exception $e) {
        error_log("Error getting top categories: " . $e->getMessage());
        return [];
    }
}
?>