<?php
// handle/home_process.php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once __DIR__ . '/../functions/dbConnect.php';
require_once __DIR__ . '/../functions/auth.php';
require_once __DIR__ . '/../functions/home.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: ../Index.php');
    exit;
}

// Tạo kết nối
$conn = getDBConnection();

$current_month = date(format: 'Y-m');
$previous_month = date('Y-m', strtotime('-1 month'));

// Lấy dữ liệu cơ bản
$total_income = getTotalIncome($conn, $user_id);
$total_expense = getTotalExpense($conn, $user_id);
$current_balance = $total_income - $total_expense;
$recent_transactions = getRecentTransactions($conn, $user_id, 5);
$category_budgets = getCategoryBudgets($conn, $user_id,$current_month);

// Lấy dữ liệu theo tháng
$current_income_month = getTotalIncomeByMonth($conn, $user_id, $current_month);
$current_expense_month = getTotalExpenseByMonth($conn, $user_id, $current_month);
$previous_income_month = getTotalIncomeByMonth($conn, $user_id, $previous_month);
$previous_expense_month = getTotalExpenseByMonth($conn, $user_id, $previous_month);

// Tính phần trăm thay đổi
$income_change_percent = calculateChangePercent($current_income_month, $previous_income_month);
$expense_change_percent = calculateChangePercent($current_expense_month, $previous_expense_month);

// Tính số dư và phần trăm thay đổi số dư
$previous_balance = getBalanceUntilMonth($conn, $user_id, $previous_month);
$balance_change_percent = calculateChangePercent($current_balance, $previous_balance);

// Tính tỷ lệ chi tiêu
if ($current_income_month > 0) {
    $budget_percentage = ($current_expense_month / $current_income_month) * 100;
} else {
    $budget_percentage = 0;
}

// Phân loại màu sắc cho progress bar
if ($budget_percentage <= 60) {
    $progress_class = 'bg-success'; 
} elseif ($budget_percentage <= 80) {
    $progress_class = 'bg-warning';
} else {
    $progress_class = 'bg-danger'; 
}

// Đóng kết nối
mysqli_close($conn);

// Hàm tính phần trăm thay đổi
function calculateChangePercent($current, $previous) {
    if ($previous != 0) {
        return round((($current - $previous) / abs($previous)) * 100, 1);
    }
    return $current > 0 ? 100 : 0;
}
?>