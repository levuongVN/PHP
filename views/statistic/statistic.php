<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../handle/home_process.php';
require_once '../../handle/statistic_process.php';
require_once(__DIR__ . "/../../functions/auth.php") ;
isLoggedIn();
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../../css/sideBar.css">
    <link rel="stylesheet" href="../../css/statistic.css">
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
                    <!-- Left Column -->
                    <div class="col-lg-8">
                        <!-- Income vs Expense Chart -->
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>Thu nhập vs Chi tiêu</h4>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="incomeExpenseChart"></canvas>
                            </div>
                        </div>

                        <!-- Expense by Category -->
                        <div class="chart-container">
                            <h4 class="mb-4">Chi tiêu theo danh mục</h4>
                            <div class="chart-wrapper">
                                <canvas id="categoryExpenseChart"></canvas>
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

        <script>
            // Dữ liệu từ PHP
            const chartData = <?= json_encode($chart_data) ?>;
            const topCategories = <?= json_encode($top_categories) ?>;

            // Biểu đồ Thu nhập vs Chi tiêu
            const incomeExpenseCtx = document.getElementById('incomeExpenseChart').getContext('2d');
            const incomeExpenseChart = new Chart(incomeExpenseCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Thu nhập',
                            data: chartData.income,
                            borderColor: '#28a745',
                            backgroundColor: 'rgba(40, 167, 69, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Chi tiêu',
                            data: chartData.expense,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    return context.dataset.label + ': ' + context.parsed.y.toLocaleString('vi-VN') + ' ₫';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function (value) {
                                    return value.toLocaleString('vi-VN') + ' ₫';
                                }
                            }
                        }
                    }
                }
            });

            // Biểu đồ Chi tiêu theo danh mục
            const categoryExpenseCtx = document.getElementById('categoryExpenseChart').getContext('2d');
            const categoryExpenseChart = new Chart(categoryExpenseCtx, {
                type: 'doughnut',
                data: {
                    labels: topCategories.map(item => item.category_name),
                    datasets: [{
                        data: topCategories.map(item => item.total_spent),
                        backgroundColor: topCategories.map(item => item.display_color),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return context.label + ': ' + value.toLocaleString('vi-VN') + ' ₫ (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        </script>
</body>

</html>