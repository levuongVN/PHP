<?php

function getRemindersById($conn, $id)
{
    $sql = "
    SELECT 
    r.id,
    r.percentage_spent,
    r.due_date,
    r.budget_id,
    r.is_read,
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
function getAmountBudget($conn, $user_id, $budget_id){
    $sql = "
        SELECT b.amount, COALESCE(SUM(t.amount),0) as spent, c.name
        FROM budgets b
        LEFT JOIN categories c ON c.id = b.category_id
        LEFT JOIN transactions t ON t.category_id = c.id
        WHERE b.user_id = ? AND b.id = ?
        GROUP BY b.id, b.amount, c.name;
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $budget_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $amountBudgets = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $amountBudgets[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $amountBudgets;

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
function updateReminder($conn, $id, $user_id, $budget_id, $percentage_spent, $due_date, $is_paid = false)
{
    try {
        // Lấy old_percentage_spent
        $sql_old = "SELECT old_percentage_spent FROM reminders WHERE user_id = ? AND id = ?";
        $stmt = mysqli_prepare($conn, $sql_old);
        mysqli_stmt_bind_param($stmt, 'ii', $user_id, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $old_percentage);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        $is_paid_bool = $is_paid ? 1 : 0;

        if (is_null($old_percentage)) {
            $sql_update = "
                UPDATE reminders 
                SET old_percentage_spent = percentage_spent,
                    budget_id = ?, 
                    percentage_spent = ?, 
                    due_date = ?, 
                    is_paid = ?
                WHERE user_id = ? AND id = ?";
            
            $final_percentage = $percentage_spent;

        } else {
            $sql_update = "
                UPDATE reminders 
                SET budget_id = ?, 
                    percentage_spent = ?, 
                    due_date = ?, 
                    is_paid = ?
                WHERE user_id = ? AND id = ?";

            $final_percentage = $old_percentage;
        }

        // Execute update
        $stmt2 = mysqli_prepare($conn, $sql_update);
        mysqli_stmt_bind_param($stmt2, "issiii",
            $budget_id, $final_percentage, $due_date, $is_paid_bool, $user_id, $id
        );

        $result = mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);

        return $result;

    } catch (Exception $e) {
        error_log("Error updating reminder: " . $e->getMessage());
        return false;
    }
}

?>