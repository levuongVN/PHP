<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once(__DIR__ . "/../functions/reminder.php");
require_once(__DIR__ . "/../functions/dbConnect.php");
require_once(__DIR__ . "/../functions/budget.php");
$conn = getDbConnection();
$id = $_SESSION['user_id'];
$getReminderById = getRemindersById($conn, $id);
$categories = getCategories(conn: $conn, user_id: $id);
// Phân loại dữ liệu
$budgetReminders = [];
$billReminders = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'create_reminder':
            handleCreateReminder($conn, $id);
            break;

        case 'edit_reminder':
            handleEditReminder($conn, $id);
            break;

        case 'delete_reminder':

            break;

        default:
            $_SESSION['error'] = 'Hành động không hợp lệ';
            header('Location: ../../views/reminder/reminder.php');
            exit;
    }
}

foreach ($getReminderById as $reminder) {
    if ($reminder['percentage_spent'] === null) {
        $billReminders[] = $reminder;
    } else {
        $budgetReminders[] = $reminder;
    }
}
function formatCurrency($amount)
{
    return number_format(floatval($amount), 0, ',', '.') . ' ₫';
}

function getBudgetAlertType($percentage)
{
    $percent = floatval($percentage);
    if ($percent >= 100) {
        return [
            'type' => 'danger',
            'icon' => 'exclamation-triangle',
            'message' => 'Vượt ngân sách'
        ];
    } elseif ($percent >= 95) {
        return [
            'type' => 'danger',
            'icon' => 'exclamation-circle',
            'message' => 'Sắp vượt ngân sách'
        ];
    } elseif ($percent >= 80) {
        return [
            'type' => 'warning',
            'icon' => 'exclamation',
            'message' => 'Cảnh báo chi tiêu'
        ];
    } elseif ($percent >= 50) {
        return [
            'type' => 'info',
            'icon' => 'chart-line',
            'message' => 'Đã sử dụng một nửa ngân sách'
        ];
    } else {
        return [
            'type' => 'success',
            'icon' => 'check-circle',
            'message' => 'Ngân sách ổn định'
        ];
    }
}

function getBillStatus($due_date)
{
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

function handleCreateReminder($conn, $user_id)
{
    $typeReminder = $_POST['reminderType'] ?? '';
    $idBudgetReminder = $_POST['budget_id'] ?? '';
    $dateReminder = $_POST['reminderDueDate'] ?? null;
    $hasError = false;

    if ($typeReminder === 'bill') {
        if (empty($dateReminder)) {
            $hasError = true;
            $_SESSION['error_reminderDueDate'] = 'Vui lòng chọn ngày đến hạn';
        } elseif ($dateReminder < date('Y-m-d')) {
            $hasError = true;
            $_SESSION['error_reminderDueDate'] = 'Ngày đến hạn không được là ngày trong quá khứ';
        } elseif ($dateReminder !== null && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateReminder)) {
            $hasError = true;
            $_SESSION['error_reminderDueDate'] = 'Ngày đến hạn không hợp lệ';
        }
    }
    if ($hasError) {
        $_SESSION['oldTypeReminder'] = $typeReminder;
        $_SESSION['oldIdBudgetReminder'] = $idBudgetReminder;
        $_SESSION['oldDateReminder'] = $dateReminder;
        header('Location: ../views/reminder/reminder.php');
        exit;
    } else {
        createReminder(
            $conn,
            $user_id,
            $idBudgetReminder,
            $typeReminder === 'budget' ? 0 : null,
            $typeReminder === 'bill' ? $dateReminder : null,
            false
        );
    }

    header('Location: ../views/reminder/reminder.php');
    exit;
}

function handleEditReminder($conn, $user_id)
{
    $id = $_POST['id'] ?? 0; 
    $Type = $_POST['editReminderType'] ?? '';
    $budget_id = $_POST['category_id'] ?? 0;
    $due_date = $_POST['reminderDueDate'] ?? null;
    $hasError = false;

    if ($Type === 'bill') {
        if (empty($due_date)) {
            $_SESSION['error_reminderDueDate'] = 'Vui lòng chọn ngày đến hạn';
            $hasError = true;
        } else {
            $due_timestamp = strtotime($due_date);
            $today_timestamp = strtotime(date('Y-m-d'));

            if ($due_timestamp === false) {
                $_SESSION['error_reminderDueDate'] = 'Ngày đến hạn không hợp lệ';
                $hasError = true;
            } elseif ($due_timestamp < $today_timestamp) {
                $_SESSION['error_reminderDueDate'] = 'Ngày đến hạn không được là ngày trong quá khứ';
                $hasError = true;
            }
        }
    } else {
        if (!empty($due_date)) {
            $_SESSION['error_reminderDueDate'] = 'Loại nhắc nhở không phù hợp với ngày đến hạn';
            $hasError = true;
        }
    }
    if ($hasError) {
        header('Location: ../views/reminder/reminder.php');
        exit;
    }else{

    }
}
?>