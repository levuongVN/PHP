<?php
// Bắt đầu session và kiểm tra đăng nhập
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../handle/home_process.php';
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ../Index.php');
    exit();
}
$theme = $_SESSION['theme'] ?? '';
$_SESSION['last_page'] = $_SERVER['REQUEST_URI'];

// Lấy thông tin người dùng từ session
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$full_name = $_SESSION['full_name'];
$login_time = $_SESSION['login_time'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyMaster - Quản lý chi tiêu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/home.css">
</head>

<body style="background: <?= $theme ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 col-xl-2 col-xxl-2 sidebar">
                <?php include 'sideBar.php' ?>
            </div>
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header d-flex justify-content-start align-items-center" style="gap: 40px;">
    <!-- Phần chào người dùng bên trái -->
    <div class="flex-shrink-0">
        <h2 class="mb-0">Xin chào, <?php echo htmlspecialchars($full_name); ?>!</h2>
        <p class="text-muted mb-0">
            Hôm nay là Thứ Tư, 15 tháng 11, 2023
        </p>
    </div>

    <!-- Phần tìm kiếm và avatar -->
    <div class="d-flex align-items-center ms-auto">
        <div class="input-group me-3" style="width: 300px;">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" placeholder="Tìm kiếm...">
        </div>
        <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($full_name) ?>&background=4361ee&color=fff"
                    class="user-avatar me-2">
                <span><?php echo htmlspecialchars($full_name); ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="./profile/profile.php"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="../handle/logout_process.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>


                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card income">
                            <div class="stats-number text-success">
                                <?= number_format($current_income_month, 0, ',', '.') ?> ₫
                            </div>
                            <div class="stats-title">Tổng thu nhập tháng này</div>
                            <div class="mt-2">
                                <?php if ($income_change_percent >= 0): ?>
                                    <span class="text-success"><i
                                            class="fas fa-arrow-up"></i><?= $income_change_percent ?>%</span>
                                <?php else: ?>
                                    <span class="text-danger"><i
                                            class="fas fa-arrow-down"></i><?= abs($income_change_percent) ?>%</span>
                                <?php endif; ?>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card expense">
                            <div class="stats-number text-danger">
                                <?= number_format($current_expense_month, 0, ',', '.') ?> ₫
                            </div>
                            <div class="stats-title">Tổng chi tiêu tháng này</div>
                            <div class="mt-2">
                                <?php if ($expense_change_percent >= 0): ?>
                                    <span class="text-success"><i
                                            class="fas fa-arrow-up"></i><?= $expense_change_percent ?>%</span>
                                <?php else: ?>
                                    <span class="text-danger"><i
                                            class="fas fa-arrow-down"></i><?= abs($expense_change_percent) ?>%</span>
                                <?php endif; ?>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card balance">
                            <div class="stats-number text-warning"><?= number_format($current_balance, 0, ',', '.') ?> ₫
                            </div>
                            <div class="stats-title">Số dư hiện tại</div>
                            <div class="mt-2">
                                <?php if (abs($balance_change_percent) < 0.1): ?>
                                    <?php if ($current_balance < 0): ?>
                                        <span class="text-danger"><i
                                                class="fas fa-arrow-down"></i> Số dư hiện đang âm</span>
                                    <?php else: ?>
                                        <span class="text-muted"><i class="fas fa-minus"></i> Không đổi</span>
                                    <?php endif; ?>
                                <?php elseif ($balance_change_percent >= 0): ?>
                                    <span class="text-success"><i
                                            class="fas fa-arrow-up"></i><?= $balance_change_percent ?>%</span>
                                <?php else: ?>
                                    <span class="text-danger"><i
                                            class="fas fa-arrow-down"></i><?= abs($balance_change_percent) ?>%</span>
                                <?php endif; ?>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number"><?= number_format($budget_percentage, 0) ?>%</div>
                            <div class="stats-title">Tỷ lệ chi</div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar <?= $progress_class ?>" role="progressbar"
                                    style="width: <?= min($budget_percentage, 100) ?>%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Recent Transactions -->
                    <div class="col-lg-8">
                        <div class="recent-transactions">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>Giao dịch gần đây</h4>
                                <a href="transaction/transaction_index.php" class="btn btn-primary btn-sm">Xem tất cả</a>
                            </div>

                            <div class="transaction-list">
                                <?php if (!empty($recent_transactions)): ?>
                                    <?php foreach ($recent_transactions as $transaction): ?>
                                        <?php
                                        $is_income = $transaction['type'] === 'income';
                                        $icon_class = $is_income ? 'income' : 'expense';
                                        $amount_class = $is_income ? 'income' : 'expense';
                                        $amount_sign = $is_income ? '+' : '-';
                                        $default_icon = $is_income ? 'fa-money-bill-wave' : 'fa-shopping-cart';
                                        ?>
                                        <div class="transaction-item">
                                            <div class="d-flex align-items-center">
                                                <div class="transaction-icon <?= $icon_class ?>">
                                                    <i class="fas <?= $transaction['icon'] ?? $default_icon ?>"></i>
                                                </div>
                                                <div class="transaction-details">
                                                    <div class="transaction-title">
                                                        <?= htmlspecialchars($transaction['description'] ?? $transaction['category_name']) ?>
                                                    </div>
                                                    <div class="transaction-category">
                                                        <?= htmlspecialchars($transaction['category_name']) ?>
                                                    </div>
                                                    <small class="text-muted">
                                                        <?= date('d/m/Y', strtotime($transaction['transaction_date'])) ?>
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="transaction-amount <?= $amount_class ?>">
                                                <?= $amount_sign ?>         <?= number_format($transaction['amount'], 0, ',', '.') ?> ₫
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="text-center py-5">
                                        <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                        <p class="text-muted mb-3">Chưa có giao dịch nào</p>
                                        <a href="./transaction/transaction_index.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Thêm giao dịch đầu tiên
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Budget -->
                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="quick-actions">
                            <h4 class="mb-4">Thao tác nhanh</h4>
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="./transaction/transaction_index.php" class="action-btn">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Thêm thu</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="./transaction/transaction_index.php" class="action-btn">
                                        <i class="fas fa-minus-circle"></i>
                                        <span>Thêm chi</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="./statistic/statistic.php" class="action-btn">
                                        <i class="fas fa-chart-pie"></i>
                                        <span>Báo cáo</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="" class="action-btn">
                                        <i class="fas fa-bell"></i>
                                        <span>Nhắc nhở</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Budget Progress -->
                        <div class="chart-container">
                            <h4 class="mb-4">Ngân sách tháng <?= date('m/Y') ?></h4>

                            <?php if (!empty($category_budgets)): ?>
                                <?php foreach ($category_budgets as $budget): ?>
                                    <?php
                                    $budget_amount = $budget['budget_amount'] ?? 0;
                                    $spent_amount = $budget['spent_amount'] ?? 0;
                                    if ($budget_amount > 0) {
                                        $percentage = ($spent_amount / $budget_amount) * 100;
                                    } else {
                                        $percentage = 0;
                                    }
                                    if ($percentage <= 60) {
                                        $progress_class = 'bg-success';
                                    } elseif ($percentage <= 80) {
                                        $progress_class = 'bg-warning';
                                    } else {
                                        $progress_class = 'bg-danger';
                                    }
                                    ?>
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>
                                                <i class="<?= $budget['icon'] ?? 'fas fa-tag' ?> me-2"
                                                    style="color: <?= $budget['color'] ?? '#4361ee' ?>"></i>
                                                <?= htmlspecialchars($budget['category_name']) ?>
                                            </span>
                                            <span>
                                                <?= number_format($spent_amount, 0, ',', '.') ?> ₫ /
                                                <?= number_format($budget_amount, 0, ',', '.') ?> ₫
                                            </span>
                                        </div>
                                        <div class="progress mb-3" style="height: 10px;">
                                            <div class="progress-bar <?= $progress_class ?>" role="progressbar"
                                                style="width: <?= min($percentage, 100) ?>%">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4">
                                    <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Chưa có ngân sách nào</p>
                                    <a href="#" class="btn btn-primary btn-sm">Thiết lập ngân sách</a>
                                </div>
                            <?php endif; ?>

                            <a href="./budget/budget.php" class="btn btn-outline-primary w-100">Quản lý ngân sách</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>