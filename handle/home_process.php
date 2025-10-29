<?php
// handle/home_process.php
session_start();
require_once '../functions/dbConnect.php';
require_once '../functions/auth.php';
require_once '../functions/home.php';

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    header('Location: ../Index.php');
    exit;
}

// Tạo kết nối
$conn = getDBConnection();

// ĐỊNH NGHĨA HÀM
function getDataFinance($conn, $user_id) {
    $total_income = getTotalIncome($conn, $user_id);
    $total_expense = getTotalExpense($conn, $user_id);
    $current_balance = $total_income - $total_expense;
    $recent_transactions = getRecentTransactions($conn, $user_id, 5);

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
        'progress_class' => $progress_class
    ];
}

$finance_data = getDataFinance($conn, $user_id);

// extract để view được truy cập các biến luôn
extract($finance_data);
?>