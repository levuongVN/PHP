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
    <?php include '../sideBar.php' ?>
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
                        <div class="dropdown me-3">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-calendar me-2"></i>Tháng 11/2023
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Tháng 10/2023</a></li>
                                <li><a class="dropdown-item" href="#">Tháng 9/2023</a></li>
                                <li><a class="dropdown-item" href="#">Tháng 8/2023</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                                data-bs-toggle="dropdown">
                                <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=4361ee&color=fff"
                                    class="user-avatar me-2">
                                <span>Nguyễn Văn A</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Cài đặt</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="filter-card">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Loại giao dịch</label>
                            <select class="form-select">
                                <option selected>Tất cả</option>
                                <option>Thu nhập</option>
                                <option>Chi tiêu</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select">
                                <option selected>Tất cả danh mục</option>
                                <option>Ăn uống</option>
                                <option>Mua sắm</option>
                                <option>Di chuyển</option>
                                <option>Giải trí</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Từ ngày</label>
                            <input type="date" class="form-control" value="2023-11-01">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Đến ngày</label>
                            <input type="date" class="form-control" value="2023-11-30">
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button class="btn btn-primary"><i class="fas fa-filter me-2"></i>Áp dụng bộ lọc</button>
                        <button class="btn btn-outline-secondary ms-2"><i class="fas fa-redo me-2"></i>Đặt lại</button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="row">
                    <div class="col-md-3">
                        <div class="stats-card income">
                            <div class="stats-number text-success">15.000.000 ₫</div>
                            <div class="stats-title">Tổng thu nhập</div>
                            <div class="mt-2">
                                <span class="text-success"><i class="fas fa-arrow-up"></i> 12.5%</span>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card expense">
                            <div class="stats-number text-danger">10.250.000 ₫</div>
                            <div class="stats-title">Tổng chi tiêu</div>
                            <div class="mt-2">
                                <span class="text-danger"><i class="fas fa-arrow-up"></i> 8.3%</span>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card balance">
                            <div class="stats-number text-warning">4.750.000 ₫</div>
                            <div class="stats-title">Số dư cuối kỳ</div>
                            <div class="mt-2">
                                <span class="text-success"><i class="fas fa-arrow-up"></i> 4.2%</span>
                                <span class="text-muted"> so với tháng trước</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card info">
                            <div class="stats-number text-info">68%</div>
                            <div class="stats-title">Tỷ lệ chi/ngân sách</div>
                            <div class="progress mt-2" style="height: 8px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 68%"></div>
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

                    <!-- Right Column -->
                    <div class="col-lg-4">
                        <!-- Top Categories -->
                        <div class="chart-container">
                            <h4 class="mb-0">Danh mục chi tiêu nhiều nhất</h4>
                            
                            <div class="mb-4">
                                <div class="category-item">
                                    <div class="d-flex align-items-center">
                                        <div class="category-color" style="background-color: #4361ee;"></div>
                                        <span>Ăn uống</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">3.250.000 ₫</div>
                                        <small class="text-muted">32% tổng chi</small>
                                    </div>
                                </div>
                                <div class="progress progress-thin mt-1">
                                    <div class="progress-bar" role="progressbar" style="width: 32%; background-color: #4361ee;"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="category-item">
                                    <div class="d-flex align-items-center">
                                        <div class="category-color" style="background-color: #f72585;"></div>
                                        <span>Mua sắm</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">2.150.000 ₫</div>
                                        <small class="text-muted">21% tổng chi</small>
                                    </div>
                                </div>
                                <div class="progress progress-thin mt-1">
                                    <div class="progress-bar" role="progressbar" style="width: 21%; background-color: #f72585;"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="category-item">
                                    <div class="d-flex align-items-center">
                                        <div class="category-color" style="background-color: #4cc9f0;"></div>
                                        <span>Di chuyển</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">1.850.000 ₫</div>
                                        <small class="text-muted">18% tổng chi</small>
                                    </div>
                                </div>
                                <div class="progress progress-thin mt-1">
                                    <div class="progress-bar" role="progressbar" style="width: 18%; background-color: #4cc9f0;"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="category-item">
                                    <div class="d-flex align-items-center">
                                        <div class="category-color" style="background-color: #f8961e;"></div>
                                        <span>Giải trí</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">1.250.000 ₫</div>
                                        <small class="text-muted">12% tổng chi</small>
                                    </div>
                                </div>
                                <div class="progress progress-thin mt-1">
                                    <div class="progress-bar" role="progressbar" style="width: 12%; background-color: #f8961e;"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="category-item">
                                    <div class="d-flex align-items-center">
                                        <div class="category-color" style="background-color: #7209b7;"></div>
                                        <span>Khác</span>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold">1.500.000 ₫</div>
                                        <small class="text-muted">15% tổng chi</small>
                                    </div>
                                </div>
                                <div class="progress progress-thin mt-1">
                                    <div class="progress-bar" role="progressbar" style="width: 15%; background-color: #7209b7;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>