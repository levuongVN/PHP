<?php

function getRemindersById($conn, $id)
{
    $sql = "
    SELECT 
    r.id,
    r.percentage_spent,
    r.due_date,
    r.budget_id,
    c.name,
    b.amount,
    COALESCE(SUM(t.amount), 0) as spent_amount
    FROM reminders r
    LEFT JOIN budgets b ON r.budget_id = b.id
    LEFT JOIN categories c ON b.category_id = c.id
    LEFT JOIN transactions t ON c.id = t.category_id AND t.user_id = ?
    WHERE r.user_id = ?
    GROUP BY r.id, r.percentage_spent, r.due_date, c.name, b.amount
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id, $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $reminders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reminders[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $reminders;
}
function createReminder($conn, $user_id, $budget_id, $percentage_spent, $due_date, $is_paid = false) 
{
    try {
        $created_at = date('Y-m-d');
        
        $sql = "INSERT INTO reminders (user_id, budget_id, percentage_spent, due_date, is_paid, created_at) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $sql);
        
        $is_paid_bool = $is_paid ? 1 : 0;
        
        mysqli_stmt_bind_param($stmt, "iissis", $user_id, $budget_id, $percentage_spent, $due_date, $is_paid_bool, $created_at);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        return $result;
    } catch (Exception $e) {
        error_log("Error creating reminder: " . $e->getMessage());
        return false;
    }
}
function updateReminder($conn, $id, $user_id, $budget_id, $due_date, $is_paid = false)
{
    try {
        $sql = "UPDATE reminders 
                SET budget_id =? , due_date = ?, is_paid = ? 
                WHERE user_id = ? AND id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        $is_paid_bool = $is_paid ? 1 : 0;
        mysqli_stmt_bind_param($stmt, "isiii", $budget_id, $due_date, $is_paid_bool, $user_id, $id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        return $result;
    } catch (Exception $e) {
        error_log("Error updating reminder: " . $e->getMessage());
        return false;
    }
}
?>