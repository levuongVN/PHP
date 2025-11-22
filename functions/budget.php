<?php


function getCategoryBudgetsAllTime($conn, $user_id)
{
    try {
        $sql = "
            SELECT 
            c.name as category_name,
            c.color,
            c.icon,
            b.id,
            b.month,
            c.id as cate_id,
            COALESCE(b.amount, 0) as budget_amount,
            COALESCE(SUM(CASE 
            WHEN YEAR(t.transaction_date) = YEAR(b.month) 
            AND MONTH(t.transaction_date) = MONTH(b.month) 
            THEN t.amount ELSE 0 
            END), 0) as spent_amount
            FROM categories c
            LEFT JOIN budgets b ON c.id = b.category_id AND b.user_id = ? AND b.is_deleted = 0
            LEFT JOIN transactions t ON c.id = t.category_id AND t.user_id = ?
            WHERE (c.user_id = ? OR c.user_id IS NULL) AND b.category_id = c.id
            GROUP BY c.id, c.name, c.color, c.icon, b.id, b.month, b.amount
            ORDER BY b.month DESC, c.name;
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
        $sql = "SELECT 
                c.id,
                c.name,
                c.type,
                c.created_at,
                b.id as budget_id,
                b.amount as budget_amount,
                b.month as budget_month,
                b.created_at as budget_created_at
                FROM categories c
                LEFT JOIN budgets b ON c.id = b.category_id
                WHERE c.user_id = ?
                ORDER BY c.name, b.month DESC;";
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
        $category_id = null;
        $sql_check = "SELECT id FROM categories WHERE user_id = ? AND name = ? AND type = ?";
        $stmt_check = mysqli_prepare($conn, $sql_check);
        mysqli_stmt_bind_param($stmt_check, "iss", $user_id, $nameCate, $type);
        mysqli_stmt_execute($stmt_check);
        $result = mysqli_stmt_get_result($stmt_check);

        if ($row = mysqli_fetch_assoc($result)) {
            // Category đã tồn tại -> dùng category_id có sẵn
            $category_id = $row['id'];
            mysqli_stmt_close($stmt_check);
        } else {
            // Category chưa tồn tại -> tạo mới
            mysqli_stmt_close($stmt_check);
            $sql = "INSERT INTO categories (user_id, name, type, created_at) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "isss", $user_id, $nameCate, $type, $createdAt);
            mysqli_stmt_execute($stmt);
            $category_id = mysqli_insert_id($conn);
            mysqli_stmt_close($stmt);
        }
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
function createBudgetAvailable($conn, $user_id, $cate_id){
    try {
        $sql = "UPDATE budgets SET is_deleted = 0 WHERE user_id = ? AND category_id = ?;";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $cate_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    } catch (Exception $e) {
        error_log("Error setting create budget available: " . $e->getMessage());
        return false;
    }
}
function deleteBudget($conn, $user_id, $cate_id)
{
    try {
        // 1. Xóa ngân sách
        $sql = "UPDATE budgets SET is_deleted = 1 WHERE user_id = ? AND category_id = ?;";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $user_id, $cate_id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if (!$result) {
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