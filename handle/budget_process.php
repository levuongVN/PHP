<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '/../functions/dbConnect.php');
require_once(__DIR__ . '/../functions/budget.php');
require_once(__DIR__ . '/../functions/home.php');
session_start();
$user_id = $_SESSION['user_id'];
$current_month = date(format: 'Y-m');
$conn = getDBConnection();


$category_budgets = getCategoryBudgetsAllTime($conn, $user_id);
$category_budgetsMonth = getCategoryBudgets($conn, $user_id, $current_month);
$categories = getCategories($conn, $user_id);

$total_budget = 0;
$total_spent = 0;
$total_remaining;
$budgeted_categories_count = 0;

foreach ($category_budgets as $budget) {
    $total_budget += $budget['budget_amount'];
    $total_spent += $budget['spent_amount'];

    // Chỉ đếm các danh mục có ngân sách > 0
    if ($budget['budget_amount'] > 0) {
        $budgeted_categories_count++;
    }
}

$total_remaining = $total_budget - $total_spent;


// create budget
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    
    // Lấy dữ liệu từ form
    $category_name = trim($_POST['category'] ?? '');
    $budget_amount = $_POST['budget_amount'] ?? '';
    $budget_date = $_POST['budget_date'] ?? date('Y-m-d');

    // Lưu giá trị cũ để hiển thị lại
    $_SESSION['old_category'] = $category_name;
    $_SESSION['old_budget_amount'] = $budget_amount;

    $hasError = false;

    // Kiểm tra lỗi
    if (empty($category_name)) {
        $_SESSION['error_category'] = 'Vui lòng nhập danh mục.';
        $hasError = true;
    }

    if (empty($budget_amount)) {
        $_SESSION['error_budget_amount'] = 'Vui lòng nhập số tiền ngân sách.';
        $hasError = true;
    }

    if ($hasError) {
        header('Location: ../views/budget/budget.php');
        exit;
    }

    // Thành công - xóa session cũ
    unset($_SESSION['old_category']);
    unset($_SESSION['old_budget_amount']);
    unset($_SESSION['error_category']);
    unset($_SESSION['error_budget_amount']);

    $_SESSION['success'] = 'Thêm ngân sách thành công!';
    header('Location: /PHP/views/budget/budget.php');
    exit;
}
?>