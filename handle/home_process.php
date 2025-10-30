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

// ĐỊNH NGHĨA HÀM TỔNG HỢP
function getDataFinance($conn, $user_id) {
    $total_income = getTotalIncome($conn, $user_id);
    $total_expense = getTotalExpense($conn, $user_id);
    $current_balance = $total_income - $total_expense;
    $recent_transactions = getRecentTransactions($conn, $user_id, 5);
    $category_budgets = getCategoryBudgets($conn, $user_id);

    // Tính phần trăm chi tiêu so với thu nhập
    if ($total_income > 0) {
        $budget_percentage = ($total_expense / $total_income) * 100;
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

    return [
        'total_income' => $total_income,
        'total_expense' => $total_expense,
        'current_balance' => $current_balance,
        'recent_transactions' => $recent_transactions,
        'budget_percentage' => $budget_percentage,
        'progress_class' => $progress_class,
        'category_budgets' => $category_budgets
    ];
}
$current_month = date('Y-m');
$previous_month = date('Y-m', strtotime('-1 month'));

// Tính phần trăm thay đổi thu nhập
$current_income = getTotalIncomeByMonth($conn, $user_id, $current_month);
$previous_income = getTotalIncomeByMonth($conn, $user_id, $previous_month);

if ($previous_income != 0) {
    $income_change_percent = (($current_income - $previous_income) / $previous_income) * 100;
} else {
    
    $income_change_percent = $current_income > 0 ? 100 : 0;
}

$income_change_percent = round($income_change_percent, 1);

$current_expense = getTotalExpenseByMonth($conn, $user_id, $current_month);
$previous_expense = getTotalExpenseByMonth($conn, $user_id, $previous_month);

if ($previous_expense != 0) {
    $expense_change_percent = (($current_expense - $previous_expense) / $previous_expense) * 100;
} else {
    $expense_change_percent = $current_expense > 0 ? 100 : 0;
}

$expense_change_percent = round($expense_change_percent, 1);

$current_balance_month = $current_income - $current_expense;
$previous_balance_month = $previous_income - $previous_expense;

if ($previous_balance_month != 0) {
    $balance_change_percent = (($current_balance_month - $previous_balance_month) / $previous_balance_month) * 100;
} else {
    $balance_change_percent = $current_balance_month > 0 ? 100 : 0;
}
$balance_change_percent = round($balance_change_percent, 1);

// GỌI HÀM VÀ EXTRACT DỮ LIỆU
$finance_data = getDataFinance($conn, $user_id);

// Đóng kết nối
mysqli_close($conn);

// Extract dữ liệu để view truy cập được các biến
extract($finance_data);
?>