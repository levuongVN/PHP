<?php

function getRemindersById($conn, $id)
{
    $sql = "
    SELECT r.id,r.percentage_spent,r.due_date , c.name, b.amount
    FROM reminders r
    LEFT JOIN budgets b ON r.budget_id = b.id
    LEFT JOIN categories c ON b.category_id = c.id
    where r.user_id = ?;
    ";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $reminders = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $reminders[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $reminders;
}
?>