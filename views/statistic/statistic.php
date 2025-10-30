<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../handle/home_process.php';
require_once '../../handle/statistic_process.php';

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
    <title>MoneyMaster - Thống kê & Báo cáo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
            --info: #7209b7;
            --light: #f8f9fa;
            --dark: #212529;
        }

        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            min-height: 100vh;
            position: fixed;
        }

        .sidebar .logo {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            transition: transform 0.3s;
            border-left: 4px solid var(--primary);
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-card.income {
            border-left-color: var(--success);
        }

        .stats-card.expense {
            border-left-color: var(--danger);
        }

        .stats-card.balance {
            border-left-color: var(--warning);
        }

        .stats-card.info {
            border-left-color: var(--info);
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stats-title {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            height: 100%;
        }

        .chart-placeholder {
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            border-radius: 10px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-color {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .progress-thin {
            height: 6px;
        }

        .filter-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .comparison-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .comparison-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }

        .top-categories-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
            height: 100%;
        }

        .category-item {
            display: flex;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .category-item:last-child {
            border-bottom: none;
        }

        .category-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            color: white;
            font-size: 16px;
        }

        .category-details {
            flex-grow: 1;
        }

        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .category-name {
            font-weight: 600;
            color: var(--dark);
        }

        .category-amount {
            font-weight: bold;
            color: var(--dark);
        }

        .category-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
        }

        .progress-bar-container {
            flex-grow: 1;
            margin: 0 10px;
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 3px;
        }

        .category-percentage {
            color: #6c757d;
            font-weight: 500;
            min-width: 40px;
            text-align: right;
        }

        .budget-info {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 2px;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <?php include '../sideBar.php'; ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Thống kê & Báo cáo</h2>
                        <p class="text-muted mb-0">Phân tích chi tiêu và thu nhập của bạn</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($full_name) ?>&background=4361ee&color=fff"
                                    class="user-avatar me-2">
                                <span><?php echo htmlspecialchars($full_name); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Cài đặt</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../../handle/logout_process.php"><i
                                            class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards (giữ nguyên) -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card income">
                            <div class="stats-number text-success"><?= number_format($current_income, 0, ',', '.') ?> ₫
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
                            <div class="stats-number text-danger"><?= number_format($current_expense, 0, ',', '.') ?> ₫
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
                                <?php if ($balance_change_percent >= 0): ?>
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
                    <!-- Left Column (giữ nguyên) -->
                    <div class="col-lg-8">
                        <!-- Income vs Expense Chart -->
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>Thu nhập vs Chi tiêu</h4>
                                <div>
                                    <button class="btn btn-outline-secondary btn-sm active">Tháng</button>
                                    <button class="btn btn-outline-secondary btn-sm">Quý</button>
                                    <button class="btn btn-outline-secondary btn-sm">Năm</button>
                                </div>
                            </div>
                            <div class="chart-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-chart-line fa-3x mb-3"></i>
                                    <p>Biểu đồ so sánh thu nhập và chi tiêu</p>
                                </div>
                            </div>
                        </div>

                        <!-- Expense by Category -->
                        <div class="chart-container">
                            <h4 class="mb-4">Chi tiêu theo danh mục</h4>
                            <div class="chart-placeholder">
                                <div class="text-center">
                                    <i class="fas fa-chart-pie fa-3x mb-3"></i>
                                    <p>Biểu đồ phân bổ chi tiêu theo danh mục</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <!-- Top Categories -->
                        <div class="top-categories-card">
                            <h4 class="mb-4">Danh mục chi tiêu hàng đầu</h4>
                            <?php foreach ($top_categories as $value): ?>
                                <div class="category-item">
                                    <div class="category-icon" style="background-color: <?= $value['display_color'] ?>">
                                        <i class="<?= $value['icon'] ?>"></i>
                                    </div>
                                    <div class="category-details">
                                        <div class="category-header">
                                            <span class="category-name"><?= $value['category_name'] ?></span>
                                            <span
                                                class="category-amount"><?= number_format($value['total_spent'], 0, ',', '.') ?>
                                                ₫</span>
                                        </div>
                                        <div class="category-progress">
                                            <span
                                                class="budget-info"><?= number_format($value['total_spent'], 0, ',', '.') ?>
                                                ₫ / <?= number_format($value['total_budget'], 0, ',', '.') ?> ₫</span>
                                            <div class="progress-bar-container">
                                                <?php
                                                $percentage = $value['total_budget'] > 0 ?
                                                    min(($value['total_spent'] / $value['total_budget']) * 100, 100) : 0;
                                                ?>
                                                <div class="progress-bar"
                                                    style="width: <?= $percentage ?>%; background-color: <?= $value['display_color'] ?>">
                                                </div>
                                            </div>
                                            <span class="category-percentage"><?= number_format($percentage, 0) ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>