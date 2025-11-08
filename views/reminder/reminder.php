<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhắc Nhở Ngân Sách - Quản Lý Tài Chính</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --warning: #f9c74f;
            --danger: #f94144;
            --success: #43aa8b;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fb;
            color: var(--dark);
            line-height: 1.6;
            min-height: 100vh;
        }

        .main-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-column {
            width: 250px;
            flex-shrink: 0;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .content-column {
            flex: 1;
            padding: 20px;
            overflow-x: auto;
        }

        .create-reminder-btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: medium;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-left: auto;
        }

        .create-reminder-btn:hover {
            background-color: #3a56d4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
        }

        .container-full {
            width: 100%;
            max-width: none;
            margin: 0;
            padding: 0 20px;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 25px;
            width: 100%;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 20px;
            margin-bottom: 20px;
            width: 100%;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
        }

        .alert-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            width: 100%;
        }

        .alert-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .alert-warning {
            background-color: rgba(249, 199, 79, 0.1);
            border-left: 4px solid var(--warning);
        }

        .alert-danger {
            background-color: rgba(249, 65, 68, 0.1);
            border-left: 4px solid var(--danger);
        }

        .alert-success {
            background-color: rgba(67, 170, 139, 0.1);
            border-left: 4px solid var(--success);
        }

        .alert-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .warning-icon {
            background-color: var(--warning);
            color: white;
        }

        .danger-icon {
            background-color: var(--danger);
            color: white;
        }

        .success-icon {
            background-color: var(--success);
            color: white;
        }

        .alert-content {
            flex: 1;
            min-width: 0;
            /* Prevent flex item from overflowing */
        }

        .alert-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .alert-desc {
            color: var(--gray);
            font-size: 0.9rem;
        }

        .alert-meta {
            display: flex;
            justify-content: space-between;
            margin-top: 8px;
            font-size: 0.85rem;
            color: var(--gray);
        }

        .budget-progress {
            margin-top: 10px;
        }

        .progress-bar {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            width: 100%;
        }

        .progress-value {
            height: 100%;
            border-radius: 3px;
        }

        .progress-warning {
            background-color: var(--warning);
        }

        .progress-danger {
            background-color: var(--danger);
        }

        .progress-success {
            background-color: var(--success);
        }

        .alert-actions {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .btn {
            padding: 6px 12px;
            border-radius: 6px;
            border: none;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--gray);
            color: var(--gray);
        }

        .btn:hover {
            opacity: 0.9;
        }

        .stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            border-radius: 10px;
            background-color: rgba(67, 97, 238, 0.05);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 0.85rem;
            color: var(--gray);
        }

        .filter-options {
            display: flex;
            gap: 10px;
            margin-left: 10px;
        }

        .filter-btn {
            padding: 8px 16px;
            font-size: medium;
            border-radius: 20px;
            border: 1px solid #e0e0e0;
            background-color: white;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .filter-btn.active {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }

        @media (max-width: 1200px) {
            .dashboard {
                grid-template-columns: 1fr;
                gap: 20px;
            }
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .sidebar-column {
                width: 100%;
            }

            .content-column {
                padding: 15px;
            }

            .container-full {
                padding: 0 15px;
            }

            .alert-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .alert-icon {
                margin-bottom: 10px;
            }

            .alert-actions {
                width: 100%;
                justify-content: flex-end;
                margin-top: 10px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .filter-options {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-md-3 col-lg-2 col-xl-2 col-xxl-2 sidebar">
            <?php include '../sideBar.php' ?>
        </div>
        <div class="dashboard">
            <div class="main-content">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Nhắc Nhở Ngân Sách</div>
                        <button class="create-reminder-btn" id="createReminderBtn">
                            <i class="fas fa-plus-circle"></i>
                            Tạo nhắc nhở mới
                        </button>
                        <div class="filter-options">
                            <button class="filter-btn active">Tất Cả</button>
                            <button class="filter-btn">Cảnh Báo</button>
                            <button class="filter-btn">Đã Xử Lý</button>
                        </div>
                    </div>

                    <div class="alert-list">
                        <!-- Cảnh báo nguy hiểm - Đã vượt quá ngân sách -->
                        <div class="alert-item alert-danger">
                            <div class="alert-icon danger-icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">Đã vượt quá ngân sách Ăn uống</div>
                                <div class="alert-desc">Bạn đã chi tiêu vượt 15% ngân sách tháng này. Chi tiêu hiện tại:
                                    4.600.000đ / 4.000.000đ</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-danger" style="width: 115%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span><i class="far fa-clock"></i> 2 giờ trước</span>
                                    <span>Ngân sách: 4.000.000đ</span>
                                </div>
                            </div>
                            <div class="alert-actions">
                                <button class="btn btn-primary">Xem chi tiết</button>
                                <button class="btn btn-outline">Bỏ qua</button>
                            </div>
                        </div>

                        <!-- Cảnh báo sắp vượt quá ngân sách -->
                        <div class="alert-item alert-warning">
                            <div class="alert-icon warning-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">Sắp vượt quá ngân sách Mua sắm</div>
                                <div class="alert-desc">Bạn đã sử dụng 85% ngân sách. Chi tiêu hiện tại: 2.550.000đ /
                                    3.000.000đ</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-warning" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span><i class="far fa-clock"></i> 1 ngày trước</span>
                                    <span>Ngân sách: 3.000.000đ</span>
                                </div>
                            </div>
                            <div class="alert-actions">
                                <button class="btn btn-primary">Điều chỉnh</button>
                                <button class="btn btn-outline">Bỏ qua</button>
                            </div>
                        </div>

                        <!-- Cảnh báo sắp vượt quá ngân sách -->
                        <div class="alert-item alert-warning">
                            <div class="alert-icon warning-icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">Sắp vượt quá ngân sách Giải trí</div>
                                <div class="alert-desc">Bạn đã sử dụng 78% ngân sách. Chi tiêu hiện tại: 1.560.000đ /
                                    2.000.000đ</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-warning" style="width: 78%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span><i class="far fa-clock"></i> 3 ngày trước</span>
                                    <span>Ngân sách: 2.000.000đ</span>
                                </div>
                            </div>
                            <div class="alert-actions">
                                <button class="btn btn-primary">Điều chỉnh</button>
                                <button class="btn btn-outline">Bỏ qua</button>
                            </div>
                        </div>

                        <!-- Cảnh báo đã xử lý -->
                        <div class="alert-item alert-success">
                            <div class="alert-icon success-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="alert-content">
                                <div class="alert-title">Đã điều chỉnh ngân sách Di chuyển</div>
                                <div class="alert-desc">Bạn đã tăng ngân sách lên 3.000.000đ. Chi tiêu hiện tại:
                                    2.400.000đ / 3.000.000đ</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-success" style="width: 80%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span><i class="far fa-clock"></i> 5 ngày trước</span>
                                    <span>Đã xử lý</span>
                                </div>
                            </div>
                            <div class="alert-actions">
                                <button class="btn btn-outline">Xem lại</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Tổng quan cảnh báo</div>
                    </div>
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-value">3</div>
                            <div class="stat-label">Cảnh báo đang hoạt động</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value">1</div>
                            <div class="stat-label">Cảnh báo đã xử lý</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Ngân sách cần chú ý</div>
                    </div>
                    <div class="budget-list">
                        <div class="alert-item" style="margin-bottom: 10px; padding: 12px;">
                            <div class="alert-content">
                                <div class="alert-title">Ăn uống</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-danger" style="width: 115%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span>4.600.000đ / 4.000.000đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert-item" style="margin-bottom: 10px; padding: 12px;">
                            <div class="alert-content">
                                <div class="alert-title">Mua sắm</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-warning" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span>2.550.000đ / 3.000.000đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="alert-item" style="margin-bottom: 10px; padding: 12px;">
                            <div class="alert-content">
                                <div class="alert-title">Giải trí</div>
                                <div class="budget-progress">
                                    <div class="progress-bar">
                                        <div class="progress-value progress-warning" style="width: 78%"></div>
                                    </div>
                                </div>
                                <div class="alert-meta">
                                    <span>1.560.000đ / 2.000.000đ</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Xử lý filter buttons
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', function () {
                // Xóa class active từ tất cả buttons
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Thêm class active vào button được click
                this.classList.add('active');

                // Ở đây có thể thêm logic để filter alerts thực tế
                // Dựa trên trạng thái của button
            });
        });

        // Xử lý nút "Bỏ qua" cho alerts
        document.querySelectorAll('.btn-outline').forEach(button => {
            if (button.textContent === 'Bỏ qua') {
                button.addEventListener('click', function () {
                    const alertItem = this.closest('.alert-item');
                    alertItem.style.opacity = '0.5';

                    // Ở đây có thể thêm logic để đánh dấu alert là đã bỏ qua
                    setTimeout(() => {
                        alertItem.style.display = 'none';
                    }, 300);
                });
            }
        });
    </script>
</body>

</html>