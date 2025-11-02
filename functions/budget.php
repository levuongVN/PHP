<?php 


function getCategoryBudgetsAllTime($conn, $user_id) {
    try {
        $sql = "
            SELECT 
                c.id,
                c.name as category_name,
                c.color,
                c.icon,
                COALESCE(SUM(b.amount), 0) as budget_amount,
                COALESCE(SUM(t.amount), 0) as spent_amount
            FROM categories c
            LEFT JOIN budgets b ON c.id = b.category_id AND b.user_id = ?
            LEFT JOIN transactions t ON c.id = t.category_id AND t.user_id = ? 
                AND t.type = 'expense'
            WHERE (c.user_id = ? OR c.user_id IS NULL) AND c.type = 'expense'
            GROUP BY c.id, c.name, c.color, c.icon
        ";
        
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iii", $user_id, $user_id, $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $budgets = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $budgets[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $budgets;
    } catch (Exception $e) {
        error_log("Error getting category budgets all time: " . $e->getMessage());
        return [];
    }
}

function getCategories($conn, $user_id) {
    try {
        $sql = "SELECT id, name FROM categories WHERE user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $categories = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $categories[] = $row;
        }
        
        mysqli_stmt_close($stmt);
        return $categories;
    } catch (Exception $e) {
        error_log("Error getting categories: " . $e->getMessage());
        return [];
    }
}
?>