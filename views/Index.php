<?php
// Bắt đầu session và kiểm tra đăng nhập
session_start();
require_once '../handle/home_process.php';
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header('Location: ../Index.php');
    exit();
}

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
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #f8961e;
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

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stats-title {
            color: #6c757d;
            font-size: 0.9rem;
        }

        .recent-transactions {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .transaction-icon.income {
            background: rgba(76, 201, 240, 0.2);
            color: var(--success);
        }

        .transaction-icon.expense {
            background: rgba(247, 37, 133, 0.2);
            color: var(--danger);
        }

        .transaction-details {
            flex-grow: 1;
        }

        .transaction-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .transaction-category {
            font-size: 0.8rem;
            color: #6c757d;
        }

        .transaction-amount.income {
            color: var(--success);
            font-weight: bold;
        }

        .transaction-amount.expense {
            color: var(--danger);
            font-weight: bold;
        }

        .quick-actions {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 12px;
            background: #f8f9fa;
            transition: all 0.3s;
            text-decoration: none;
            color: var(--dark);
        }

        .action-btn:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .action-btn i {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }

        .chart-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
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
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="logo">
                    <h3><i class="fas fa-wallet"></i> MoneyMaster</h3>
                </div>
                <nav class="nav flex-column mt-4">
                    <a class="nav-link active" href="#"><i class="fas fa-home"></i> Trang chủ</a>
                    <a class="nav-link" href="#"><i class="fas fa-exchange-alt"></i> Giao dịch</a>
                    <a class="nav-link" href="#"><i class="fas fa-chart-pie"></i> Thống kê</a>
                    <a class="nav-link" href="#"><i class="fas fa-tags"></i> Danh mục</a>
                    <a class="nav-link" href="#"><i class="fas fa-chart-line"></i> Ngân sách</a>
                    <a class="nav-link" href="#"><i class="fas fa-bell"></i> Nhắc nhở</a>
                    <a class="nav-link" href="#"><i class="fas fa-cog"></i> Cài đặt</a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Xin chào, <?php echo htmlspecialchars($full_name); ?>!</h2>
                        <p class="text-muted mb-0">Hôm nay là Thứ Tư, 15 tháng 11, 2023</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="input-group me-3" style="width: 300px;">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Tìm kiếm...">
                        </div>
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

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card income">
                            <div class="stats-number text-success"><?= number_format($total_income, 0, ',', '.') ?> ₫
                            </div>
                            <div class="stats-title">Tổng thu nhập tháng này</div>
                            <div class="mt-2">
                                <span class="text-success"><i class="fas fa-arrow-up"></i> 5.2%</span>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card expense">
                            <div class="stats-number text-danger"><?= number_format($total_expense, 0, ',', '.') ?> ₫
                            </div>
                            <div class="stats-title">Tổng chi tiêu tháng này</div>
                            <div class="mt-2">
                                <span class="text-danger"><i class="fas fa-arrow-up"></i> 3.1%</span>
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
                                <span class="text-success"><i class="fas fa-arrow-up"></i> 2.1%</span>
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
                    <!-- Chart -->
                    <div class="col-lg-8">
                        <div class="chart-container">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>Thống kê chi tiêu</h4>
                                <div>
                                    <button class="btn btn-outline-secondary btn-sm active">Tháng</button>
                                    <button class="btn btn-outline-secondary btn-sm">Quý</button>
                                    <button class="btn btn-outline-secondary btn-sm">Năm</button>
                                </div>
                            </div>
                            <div class="text-center py-5">
                                <i class="fas fa-chart-bar fa-3x text-muted"></i>
                                <p class="mt-3 text-muted">Biểu đồ thống kê sẽ hiển thị ở đây</p>
                            </div>
                        </div>

                        <!-- Recent Transactions -->
                        <div class="recent-transactions">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h4>Giao dịch gần đây</h4>
                                <a href="#" class="btn btn-primary btn-sm">Xem tất cả</a>
                            </div>

                            <div class="transaction-list">
                                <div class="transaction-item">
                                    <div class="d-flex align-items-center">
                                        <div class="transaction-icon expense">
                                            <i class="fas fa-utensils"></i>
                                        </div>
                                        <div class="transaction-details">
                                            <div class="transaction-title">Ăn tối nhà hàng</div>
                                            <div class="transaction-category">Ăn uống</div>
                                        </div>
                                    </div>
                                    <div class="transaction-amount expense">- 450.000 ₫</div>
                                </div>

                                <div class="transaction-item">
                                    <div class="d-flex align-items-center">
                                        <div class="transaction-icon income">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="transaction-details">
                                            <div class="transaction-title">Lương tháng 11</div>
                                            <div class="transaction-category">Thu nhập</div>
                                        </div>
                                    </div>
                                    <div class="transaction-amount income">+ 12.000.000 ₫</div>
                                </div>

                                <div class="transaction-item">
                                    <div class="d-flex align-items-center">
                                        <div class="transaction-icon expense">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div class="transaction-details">
                                            <div class="transaction-title">Mua sắm Tiki</div>
                                            <div class="transaction-category">Mua sắm</div>
                                        </div>
                                    </div>
                                    <div class="transaction-amount expense">- 1.250.000 ₫</div>
                                </div>

                                <div class="transaction-item">
                                    <div class="d-flex align-items-center">
                                        <div class="transaction-icon expense">
                                            <i class="fas fa-gas-pump"></i>
                                        </div>
                                        <div class="transaction-details">
                                            <div class="transaction-title">Đổ xăng</div>
                                            <div class="transaction-category">Di chuyển</div>
                                        </div>
                                    </div>
                                    <div class="transaction-amount expense">- 300.000 ₫</div>
                                </div>

                                <div class="transaction-item">
                                    <div class="d-flex align-items-center">
                                        <div class="transaction-icon income">
                                            <i class="fas fa-gift"></i>
                                        </div>
                                        <div class="transaction-details">
                                            <div class="transaction-title">Tiền thưởng</div>
                                            <div class="transaction-category">Thu nhập</div>
                                        </div>
                                    </div>
                                    <div class="transaction-amount income">+ 450.000 ₫</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions & Budget -->
                    <div class="col-lg-4">
                        <!-- Quick Actions -->
                        <div class="quick-actions mb-4">
                            <h4 class="mb-4">Thao tác nhanh</h4>
                            <div class="row g-3">
                                <div class="col-6">
                                    <a href="#" class="action-btn">
                                        <i class="fas fa-plus-circle"></i>
                                        <span>Thêm thu</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="action-btn">
                                        <i class="fas fa-minus-circle"></i>
                                        <span>Thêm chi</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="action-btn">
                                        <i class="fas fa-chart-pie"></i>
                                        <span>Báo cáo</span>
                                    </a>
                                </div>
                                <div class="col-6">
                                    <a href="#" class="action-btn">
                                        <i class="fas fa-bell"></i>
                                        <span>Nhắc nhở</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Budget Progress -->
                        <div class="chart-container">
                            <h4 class="mb-4">Ngân sách tháng</h4>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ăn uống</span>
                                    <span>1.2/2.0 tr</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: 60%"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Di chuyển</span>
                                    <span>0.8/1.0 tr</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: 80%"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Giải trí</span>
                                    <span>0.9/1.0 tr</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 90%"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Mua sắm</span>
                                    <span>1.5/2.0 tr</span>
                                </div>
                                <div class="progress mb-3" style="height: 10px;">
                                    <div class="progress-bar bg-info" role="progressbar" style="width: 75%"></div>
                                </div>
                            </div>

                            <button class="btn btn-outline-primary w-100">Quản lý ngân sách</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>