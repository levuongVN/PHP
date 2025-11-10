<?php
session_start();
require_once(__DIR__ . "/../functions/reminder.php");
require_once(__DIR__ . "/../functions/dbConnect.php");

$conn = getDbConnection();
$id = $_SESSION['user_id'];
$getReminderById = getRemindersById($conn, $id);

// Phân loại dữ liệu
$budgetReminders = [];
$billReminders = [];

foreach ($getReminderById as $reminder) {
    if ($reminder['percentage_spent'] === null) {
        $billReminders[] = $reminder;
    } else {
        $budgetReminders[] = $reminder;
    }
}
function formatCurrency($amount) {
    return number_format(floatval($amount), 0, ',', '.') . ' ₫';
}

function getBudgetAlertType($percentage) {
    $percent = floatval($percentage);
    if ($percent >= 100) {
        return ['type' => 'danger', 'icon' => 'exclamation-triangle', 'message' => 'Vượt ngân sách'];
    } elseif ($percent >= 80) {
        return ['type' => 'warning', 'icon' => 'clock', 'message' => 'Sắp vượt ngân sách'];
    } elseif ($percent >= 50) {
        return ['type' => 'info', 'icon' => 'info-circle', 'message' => 'Đã sử dụng một nửa ngân sách'];
    } else {
        return ['type' => 'success', 'icon' => 'check-circle', 'message' => 'Ngân sách ổn định'];
    }
}

function getBillStatus($due_date) {
    $due = new DateTime($due_date);
    $today = new DateTime();
    $days_left = $today->diff($due)->days;
    $days_left = $today <= $due ? $days_left : -$days_left;
    
    if ($days_left < 0) {
        return ['type' => 'danger', 'icon' => 'exclamation-circle', 'message' => 'Đã quá hạn ' . abs($days_left) . ' ngày'];
    } elseif ($days_left <= 3) {
        return ['type' => 'warning', 'icon' => 'clock', 'message' => 'Còn ' . $days_left . ' ngày'];
    } else {
        return ['type' => 'success', 'icon' => 'check-circle', 'message' => 'Còn ' . $days_left . ' ngày'];
    }
}
?>