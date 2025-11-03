<?php


function getCategoryBudgetsAllTime($conn, $user_id)
{
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
            WHERE (c.user_id = ? OR c.user_id IS NULL)
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

function getCategories($conn, $user_id)
{
    try {
        $sql = "SELECT id, name, type FROM categories WHERE user_id = ?";
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
function createBudget($conn, $user_id, $nameCate, $type, $month, $amount)
{
    $createdAt = date("Y-m-d");
    try {
        // Insert vào categories
        $sql = "INSERT INTO categories (user_id, name, type, created_at) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "isss", $user_id, $nameCate, $type, $createdAt);
        mysqli_stmt_execute($stmt);

        // Lấy category_id vừa tạo
        $category_id = mysqli_insert_id($conn);
        mysqli_stmt_close($stmt);

        // Insert vào budgets
        $sqlBudget = "INSERT INTO budgets (user_id, category_id, amount, month, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmtBudget = mysqli_prepare($conn, $sqlBudget);
        mysqli_stmt_bind_param($stmtBudget, "iidss", $user_id, $category_id, $amount, $month, $createdAt);
        mysqli_stmt_execute($stmtBudget);
        mysqli_stmt_close($stmtBudget);

        return true;
    } catch (Exception $e) {
        error_log("Error creating budget: " . $e->getMessage());
        return false;
    }
}
function deleteBudget($conn, $user_id, $budget_id, $cate_id)
{
    try {

        // 1. Xóa ngân sách
        $sql = "DELETE FROM budgets WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $budget_id, $user_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$result) {
            mysqli_rollback($conn);
            return false;
        }
        $sql_delete_cate = "DELETE FROM categories WHERE id = ? AND user_id = ?";
        $stmt_delete_cate = mysqli_prepare($conn, $sql_delete_cate);
        mysqli_stmt_bind_param($stmt_delete_cate, "ii", $cate_id, $user_id);
        $result_delete_cate = mysqli_stmt_execute($stmt_delete_cate);
        mysqli_stmt_close($stmt_delete_cate);

        if (!$result_delete_cate) {
            mysqli_rollback($conn);
            return false;
        }
        return true;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log("Error deleting budget and category: " . $e->getMessage());
        return false;
    }
}
function updateBudget($conn, $user_id, $budget_id, $category_id, $amount, $date)
{
    try {
        $sql = "UPDATE budgets SET category_id = ?, amount = ?, month = ? WHERE id = ? AND user_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iisii", $category_id, $amount, $date, $budget_id, $user_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        return $result;
    } catch (Exception $e) {
        error_log("Error updating budget: " . $e->getMessage());
        return false;
    }
}
?>