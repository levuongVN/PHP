<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require_once(__DIR__ . '/../functions/dbConnect.php');
require_once(__DIR__ . '/../functions/budget.php');
require_once(__DIR__ . '/../functions/home.php');
$user_id = $_SESSION['user_id'];
$current_month = date(format: 'Y-m');
$conn = getDBConnection();


$category_budgets = getCategoryBudgetsAllTime($conn, $user_id);
$category_budgetsMonth = getCategoryBudgets($conn, $user_id, $current_month);
$categories = getCategories(conn: $conn, user_id: $user_id);

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
    $action = $_POST['action'] ?? '';
    switch ($action) {
        case 'create_budget':
            handleCreateBudget($conn, $user_id, $categories);
            break;

        case 'delete_budget':
            handleDeleteBudget($conn, $user_id);
            break;
        case 'update_budget':
            handleUpdateBudget($conn, $user_id);
            break;

        default:
            $_SESSION['error'] = 'Hành động không hợp lệ.';
            header('Location: ../views/budget/budget.php');
            exit;
    }
}
function handleCreateBudget($conn, $user_id, $categories)
{
    $user_id = $_SESSION['user_id'];

    $category_name = trim($_POST['category']);
    $category_type = trim($_POST['category_type']);
    $budget_amount = $_POST['budget_amount'];
    $budget_date = $_POST['budget_date'];
    $month = date('Y-m-d', strtotime($budget_date));

    $budget_amount_cleaned = str_replace('.', '', $budget_amount);
    $budget_amount_numeric = (int) $budget_amount_cleaned;

    $hasError = false;

    if (empty($category_name)) {
        $_SESSION['error_category'] = 'Vui lòng nhập danh mục.';
        $hasError = true;
    }

    if (empty($budget_amount)) {
        $_SESSION['error_budget_amount'] = 'Vui lòng nhập số tiền ngân sách.';
        $hasError = true;
    }

    // Kiểm tra danh mục đã tồn tại
    $category_exists = false;
    $idCategory = null;
    foreach ($categories as $category) {
        if ($category['name'] === $category_name && $category['type'] === $category_type && (date('Y-m', strtotime($category['created_at'])) == date('Y-m', strtotime($budget_date)))) {
            $category_exists = true;
            $idCategory = $category['id'];
            break;
        }
    }
    if ($category_exists) {
        $createBudgetAvailable = createBudgetAvailable($conn, $user_id, $idCategory);
        echo $createBudgetAvailable;
        // if ($createBudgetAvailable) {
        //     header('Location: ../views/budget/budget.php');
        //     exit;
        // }
        // exit;
    }

    if ($hasError) {
        $_SESSION['old_category'] = $category_name;
        $_SESSION['old_budget_amount'] = $budget_amount;
        echo $_SESSION['old_category'];
        echo $_SESSION['old_budget_amount'];
        echo $category_name;
        header('Location: ../views/budget/budget.php');
        exit;
    }

    // Nếu không có lỗi và k có cate trong category sẽ auto thêm và tiến hành tạo budget
    $result = createBudget($conn, $user_id, $category_name, $category_type, $month, $budget_amount_numeric);
    if ($result) {
        unset($_SESSION['old_category']);
        unset($_SESSION['old_budget_amount']);
        unset($_SESSION['error_category']);
        unset($_SESSION['error_budget_amount']);
        $_SESSION['success'] = 'Tạo ngân sách thành công!';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra khi thêm ngân sách.';
    }

    header('Location: ../views/budget/budget.php');
    exit;
}
function handleDeleteBudget($conn, $user_id)
{
    $budget_id = $_POST['delete_budget_id'] ?? 0;
    $cate_id = $_POST['delete_cate_id'] ?? 0;
    if ($budget_id <= 0) {
        $_SESSION['error_delete_budget'] = 'ID ngân sách không hợp lệ.';
        header('Location: ../views/budget/budget.php');
        exit;
    } else {
        $result = deleteBudget($conn, $user_id, $cate_id);
        if ($result) {
            unset($_SESSION['error_delete_budget']);
        } else {
            $_SESSION['error_delete_budget'] = 'Đã có lỗi xảy ra khi xóa ngân sách.';
        }
    }

    header('Location: ../views/budget/budget.php');
    exit;
}
function handleUpdateBudget($conn, $user_id)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $budget_id = $_POST['budget_id'] ?? 0;
    $cate_id = $_POST['category_id'] ?? 0;
    $budget_amount = $_POST['budget_amount'] ?? '';
    $budget_date = $_POST['budget_date'] ?? '';

    $hasError = false;

    // Reset errors
    unset($_SESSION['edit_error_budget_amount']);

    // Validate budget_id
    if (empty($budget_id) || $budget_id <= 0) {
        $_SESSION['error'] = 'ID ngân sách không hợp lệ.';
        $hasError = true;
    }

    // Validate category_id
    if (empty($cate_id) || $cate_id <= 0) {
        $_SESSION['edit_error_category'] = 'Vui lòng chọn danh mục.';
        $hasError = true;
    }

    // Validate amount - không được trống
    if (empty($budget_amount)) {
        $_SESSION['edit_error_budget_amount'] = 'Vui lòng nhập số tiền ngân sách.';
        $hasError = true;
    } else {
        // Xóa định dạng số (dấu phân cách hàng nghìn)
        $budget_amount_cleaned = str_replace('.', '', $budget_amount);
        $budget_amount_cleaned = str_replace(',', '', $budget_amount_cleaned);

        // Kiểm tra xem có phải là số không
        if (!is_numeric($budget_amount_cleaned)) {
            $_SESSION['edit_error_budget_amount'] = 'Số tiền ngân sách phải là số.';
            $hasError = true;
        } else {
            // Chuyển đổi sang số
            $budget_amount_numeric = (float) $budget_amount_cleaned;

            // Kiểm tra số tiền phải lớn hơn 0
            if ($budget_amount_numeric <= 0) {
                $_SESSION['edit_error_budget_amount'] = 'Số tiền ngân sách phải lớn hơn 0.';
                $hasError = true;
            }

            // Kiểm tra số tiền không quá lớn (tuỳ chọn - ví dụ 100 tỷ)
            if ($budget_amount_numeric > 100000000000) {
                $_SESSION['edit_error_budget_amount'] = 'Số tiền ngân sách quá lớn.';
                $hasError = true;
            }
        }
    }

    // Validate date
    if (empty($budget_date)) {
        $_SESSION['edit_error_budget_date'] = 'Vui lòng chọn thời gian.';
        $hasError = true;
    } else {
        // Kiểm tra định dạng date
        $date_parts = explode('-', $budget_date);
        if (count($date_parts) !== 3 || !checkdate($date_parts[1], $date_parts[2], $date_parts[0])) {
            $_SESSION['edit_error_budget_date'] = 'Định dạng thời gian không hợp lệ.';
            $hasError = true;
        }
    }

    if ($hasError) {
        $_SESSION['edit_budget_id'] = $budget_id;
        $_SESSION['edit_category_id'] = $cate_id;
        $_SESSION['edit_budget_amount'] = $budget_amount;
        $_SESSION['edit_budget_date'] = $budget_date;

        header('Location: ../views/budget/budget.php');
        exit;
    }

    // Nếu không có lỗi, tiến hành cập nhật
    $budget_amount_numeric = (float) str_replace(['.', ','], '', $budget_amount);
    $result = updateBudget($conn, $user_id, $budget_id, $cate_id, $budget_amount_numeric, $budget_date);

    if ($result) {
        // Xóa session errors và old data
        unset($_SESSION['edit_error_category']);
        unset($_SESSION['edit_error_budget_amount']);
        unset($_SESSION['edit_error_budget_date']);
        unset($_SESSION['edit_budget_id']);
        unset($_SESSION['edit_category_id']);
        unset($_SESSION['edit_budget_amount']);
        unset($_SESSION['edit_budget_date']);

        $_SESSION['success'] = 'Cập nhật ngân sách thành công!';
    } else {
        $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật ngân sách.';
    }

    header('Location: ../views/budget/budget.php');
    exit;
}
?>