<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../../handle/budget_process.php';

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
    <title>MoneyMaster - Quản lý Ngân sách</title>
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

        .budget-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .budget-table {
            width: 100%;
            border-collapse: collapse;
        }

        .budget-table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: left;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid #e9ecef;
        }

        .budget-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .budget-table tr:last-child td {
            border-bottom: none;
        }

        .budget-table tr:hover {
            background-color: #f8f9fa;
        }

        .category-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
            margin-right: 10px;
        }

        .category-name {
            font-weight: 600;
            color: var(--dark);
        }

        .budget-amount {
            font-weight: bold;
            color: var(--dark);
        }

        .spent-amount {
            color: var(--danger);
            font-weight: 500;
        }

        .remaining-amount {
            color: var(--success);
            font-weight: 500;
        }

        .progress-container {
            width: 100%;
            height: 8px;
            background-color: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 5px;
        }

        .progress-bar {
            height: 100%;
            border-radius: 4px;
        }

        .action-buttons .btn {
            padding: 5px 10px;
            margin-left: 5px;
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

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .add-budget-btn {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 10px;
            padding: 12px 25px;
            font-weight: 600;
            color: white;
            transition: all 0.3s;
        }

        .add-budget-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }

        .budget-summary {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            flex: 1;
            margin: 0 10px;
        }

        .summary-item:first-child {
            margin-left: 0;
        }

        .summary-item:last-child {
            margin-right: 0;
        }

        .summary-value {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .summary-label {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .filter-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }

        .budget-stats .stat-item {
            transition: all 0.3s ease;
        }

        .budget-stats .stat-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-group .btn.active {
            background-color: #4361ee;
            border-color: #4361ee;
            color: white;
        }

        .progress-container {
            position: relative;
        }

        .budget-table tr:hover {
            background-color: #f8f9fa;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .budget-summary {
                flex-direction: column;
            }

            .summary-item {
                margin: 5px 0;
            }
        }
    </style>
</head>

<body>
    <!-- Sidebar (giả định) -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 col-xl-2 col-xxl-2 sidebar">
                <?php include '../sideBar.php' ?>
            </div>

            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Quản lý Ngân sách</h2>
                        <p class="text-muted mb-0">Thiết lập và theo dõi ngân sách theo danh mục</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=4361ee&color=fff"
                                    class="user-avatar me-2">
                                <span><?php echo htmlspecialchars($full_name); ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Cài đặt</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="../handle/logout_process.php"><i
                                            class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Thống kê tổng quan -->
                <div class="budget-summary">
                    <div class="summary-item">
                        <div class="summary-value text-primary"><?= $budgeted_categories_count ?></div>
                        <div class="summary-label">Danh mục có ngân sách</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value text-success"><?= number_format($total_budget, 0, ".", ".") ?> ₫
                        </div>
                        <div class="summary-label">Tổng ngân sách</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value text-danger"><?= number_format($total_spent, 0, ".", ".") ?>₫</div>
                        <div class="summary-label">Đã chi tiêu</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value text-warning"><?= number_format($total_remaining, 0, ".", ".") ?> ₫
                        </div>
                        <div class="summary-label">Còn lại</div>
                    </div>
                </div>

                <!-- Card quản lý ngân sách -->
                <div class="budget-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="mb-0">Ngân sách theo danh mục</h4>
                        <button class="btn add-budget-btn" data-bs-toggle="modal" data-bs-target="#addBudgetModal">
                            <i class="fas fa-plus me-2"></i> Thêm ngân sách
                        </button>
                    </div>

                    <!-- Bộ lọc theo tháng -->
                    <div class="filter-section mb-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <label class="form-label mb-0 me-3">Lọc theo tháng:</label>
                                    <div class="input-group" style="width: 200px;">
                                        <input type="month" class="form-control" id="budgetMonthFilter"
                                            value="<?= date('Y-m') ?>">
                                        <button class="btn btn-outline-secondary" type="button" id="applyFilterBtn">
                                            <i class="fas fa-filter"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm active"
                                        data-period="current">
                                        Tháng này
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-period="previous">
                                        Tháng trước
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-period="all">
                                        Tất cả
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="budget-table">
                            <thead>
                                <tr>
                                    <th width="25%">Danh mục</th>
                                    <th width="15%">Ngân sách</th>
                                    <th width="15%">Đã chi</th>
                                    <th width="15%">Còn lại</th>
                                    <th width="20%">Tiến độ</th>
                                    <th width="10%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($category_budgetsMonth as $budget): ?>
                                    <?php
                                    // Tính phần trăm đã chi so với ngân sách
                                    $budget_amount = $budget['budget_amount'];
                                    $spent_amount = $budget['spent_amount'];
                                    $remaining_amount = $budget_amount - $spent_amount;

                                    // Xử lý trường hợp ngân sách = 0
                                    if ($budget_amount == 0) {
                                        if ($spent_amount > 0) {
                                            // Ngân sách = 0 nhưng có chi tiêu -> Vượt ngân sách
                                            $percentage = 100;
                                            $progress_color = '#f72585'; // Màu đỏ
                                            $status_text = 'Chưa đặt ngân sách';
                                            $status_class = 'text-danger';
                                            $status_icon = 'fa-exclamation-triangle';
                                        } else {
                                            // Ngân sách = 0 và không chi tiêu -> An toàn
                                            $percentage = 0;
                                            $progress_color = '#4cc9f0'; // Xanh nhạt
                                            $status_text = 'Chưa đặt ngân sách';
                                            $status_class = 'text-info';
                                            $status_icon = 'fa-info-circle';
                                        }
                                    } else {
                                        // Ngân sách > 0, tính phần trăm bình thường
                                        $percentage = min(($spent_amount / $budget_amount) * 100, 100);

                                        // Xác định màu sắc và trạng thái dựa trên phần trăm
                                        if ($percentage <= 60) {
                                            $progress_color = '#4cc9f0'; // Xanh nhạt
                                            $status_text = 'An toàn';
                                            $status_class = 'text-success';
                                            $status_icon = 'fa-check-circle';
                                        } elseif ($percentage <= 80) {
                                            $progress_color = '#f8961e'; // Cam
                                            $status_text = 'Đạt ' . round($percentage) . '%';
                                            $status_class = 'text-warning';
                                            $status_icon = 'fa-info-circle';
                                        } else {
                                            $progress_color = '#f72585'; // Hồng
                                            $status_text = 'Vượt ' . round($percentage) . '%';
                                            $status_class = 'text-danger';
                                            $status_icon = 'fa-exclamation-triangle';
                                        }
                                    }

                                    // Kiểm tra số dư âm (quan trọng hơn phần trăm)
                                    if ($remaining_amount < 0) {
                                        $progress_color = '#dc3545'; // Đỏ đậm
                                        $status_text = 'Vượt hạn mức';
                                        $status_class = 'text-danger';
                                        $status_icon = 'fa-times-circle';
                                    }
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="category-icon"
                                                    style="background-color: <?= $budget['color'] ?>;">
                                                    <i class="<?= $budget['icon'] ?>"></i>
                                                </div>
                                                <span
                                                    class="category-name"><?= htmlspecialchars($budget['category_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="budget-amount"><?= number_format($budget_amount, 0, ',', '.') ?> ₫</td>
                                        <td class="spent-amount"><?= number_format($spent_amount, 0, ',', '.') ?> ₫</td>
                                        <td class="remaining-amount"><?= number_format($remaining_amount, 0, ',', '.') ?> ₫
                                        </td>
                                        <td>
                                            <div class="progress-container">
                                                <div class="progress-bar"
                                                    style="width: <?= $percentage ?>%; background-color: <?= $progress_color ?>;">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-1">
                                                <small class="text-muted"><?= round($percentage) ?>%</small>
                                                <small class="<?= $status_class ?>">
                                                    <i class="fas <?= $status_icon ?> me-1"></i>
                                                    <?= $status_text ?>
                                                </small>
                                            </div>
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-outline-primary edit-budget-btn"
                                                data-bs-toggle="modal" data-bs-target="#editBudgetModal"
                                                data-budget-id="<?= $budget['id'] ?>"
                                                data-category-id="<?= $budget['cate_id'] ?>"
                                                data-value-amount="<?= number_format((float) $budget_amount, 0, ',', '.') ?>"
                                                data-month-budget="<?= $budget['month'] ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                data-bs-target="#deleteBudgetModal" data-budget-id="<?= $budget['id'] ?>"
                                                data-cate-id="<?= $budget['cate_id'] ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Phân trang (nếu cần) -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Hiển thị 1-5 của 5 danh mục
                        </div>
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Trước</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Tiếp</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>

                <!-- Thêm JavaScript xử lý lọc -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const monthFilter = document.getElementById('budgetMonthFilter');
                        const applyFilterBtn = document.getElementById('applyFilterBtn');
                        const periodButtons = document.querySelectorAll('[data-period]');

                        // Xử lý nút áp dụng lọc
                        applyFilterBtn.addEventListener('click', function () {
                            const selectedMonth = monthFilter.value;
                            console.log('Lọc theo tháng:', selectedMonth);
                            // Gọi API hoặc xử lý lọc dữ liệu ở đây
                            filterBudgetsByMonth(selectedMonth);
                        });

                        // Xử lý nút lọc nhanh (tháng này, tháng trước, tất cả)
                        periodButtons.forEach(button => {
                            button.addEventListener('click', function () {
                                // Xóa active class từ tất cả các nút
                                periodButtons.forEach(btn => btn.classList.remove('active'));
                                // Thêm active class cho nút được click
                                this.classList.add('active');

                                const period = this.dataset.period;
                                handlePeriodFilter(period);
                            });
                        });

                        function filterBudgetsByMonth(month) {
                            // Logic lọc dữ liệu theo tháng
                            console.log('Đang lọc ngân sách tháng:', month);
                            // Ở đây bạn sẽ gọi API hoặc cập nhật dữ liệu hiển thị
                        }

                        function handlePeriodFilter(period) {
                            let targetMonth;

                            switch (period) {
                                case 'current':
                                    targetMonth = new Date().toISOString().slice(0, 7);
                                    break;
                                case 'previous':
                                    const prevMonth = new Date();
                                    prevMonth.setMonth(prevMonth.getMonth() - 1);
                                    targetMonth = prevMonth.toISOString().slice(0, 7);
                                    break;
                                case 'all':
                                    targetMonth = 'all';
                                    break;
                            }

                            if (targetMonth !== 'all') {
                                monthFilter.value = targetMonth;
                            }

                            console.log('Lọc theo kỳ:', period, 'Tháng:', targetMonth);
                            filterBudgetsByMonth(targetMonth);
                        }
                    });
                </script>
            </div>
        </div>
    </div>

    <!-- Include các modal từ file riêng -->
    <?php include 'modals/create.php'; ?>
    <?php
    $categories_for_edit = $categories;
    include 'modals/edit.php'; ?>
    <?php include 'modals/delete.php'; ?>

    <script>
        // Xử lý sự kiện khi modal hiển thị
        document.addEventListener('DOMContentLoaded', function () {
            // Xử lý nút thêm ngân sách
            const addBudgetBtn = document.querySelector('.add-budget-btn');

            // Xử lý form thêm ngân sách
            const addBudgetForm = document.getElementById('addBudgetForm');

            // Xử lý form sửa ngân sách
            const editBudgetForm = document.getElementById('editBudgetForm');

            // Xử lý xóa ngân sách
            const deleteBudgetBtn = document.querySelector('#deleteBudgetModal .btn-danger');

            // Thêm sự kiện cho nút xác nhận xóa
            if (deleteBudgetBtn) {
                deleteBudgetBtn.addEventListener('click', function () {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteBudgetModal'));
                    modal.hide();
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>