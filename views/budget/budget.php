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
            <!-- Main Content -->
             <?php include '../sideBar.php' ?>
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
                                <img src="https://ui-avatars.com/api/?name=Người+Dùng&background=4361ee&color=fff"
                                    class="user-avatar me-2">
                                <span>Người Dùng</span>
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

                <!-- Thống kê tổng quan -->
                <div class="budget-summary">
                    <div class="summary-item">
                        <div class="summary-value text-primary">5</div>
                        <div class="summary-label">Danh mục có ngân sách</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value text-success">15,000,000 ₫</div>
                        <div class="summary-label">Tổng ngân sách</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value text-danger">8,500,000 ₫</div>
                        <div class="summary-label">Đã chi tiêu</div>
                    </div>
                    <div class="summary-item">
                        <div class="summary-value text-warning">6,500,000 ₫</div>
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

                    <div class="table-responsive">
                        <table class="budget-table">
                            <thead>
                                <tr>
                                    <th width="30%">Danh mục</th>
                                    <th width="15%">Ngân sách</th>
                                    <th width="15%">Đã chi</th>
                                    <th width="15%">Còn lại</th>
                                    <th width="20%">Tiến độ</th>
                                    <th width="15%">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon" style="background-color: #4361ee;">
                                                <i class="fas fa-home"></i>
                                            </div>
                                            <span class="category-name">Nhà ở</span>
                                        </div>
                                    </td>
                                    <td class="budget-amount">5,000,000 ₫</td>
                                    <td class="spent-amount">4,200,000 ₫</td>
                                    <td class="remaining-amount">800,000 ₫</td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar" style="width: 84%; background-color: #f72585;"></div>
                                        </div>
                                        <small class="text-muted">84%</small>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBudgetModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon" style="background-color: #4cc9f0;">
                                                <i class="fas fa-utensils"></i>
                                            </div>
                                            <span class="category-name">Ăn uống</span>
                                        </div>
                                    </td>
                                    <td class="budget-amount">3,000,000 ₫</td>
                                    <td class="spent-amount">1,800,000 ₫</td>
                                    <td class="remaining-amount">1,200,000 ₫</td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar" style="width: 60%; background-color: #4cc9f0;"></div>
                                        </div>
                                        <small class="text-muted">60%</small>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBudgetModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon" style="background-color: #f8961e;">
                                                <i class="fas fa-car"></i>
                                            </div>
                                            <span class="category-name">Di chuyển</span>
                                        </div>
                                    </td>
                                    <td class="budget-amount">2,000,000 ₫</td>
                                    <td class="spent-amount">1,200,000 ₫</td>
                                    <td class="remaining-amount">800,000 ₫</td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar" style="width: 60%; background-color: #f8961e;"></div>
                                        </div>
                                        <small class="text-muted">60%</small>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBudgetModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon" style="background-color: #7209b7;">
                                                <i class="fas fa-shopping-cart"></i>
                                            </div>
                                            <span class="category-name">Mua sắm</span>
                                        </div>
                                    </td>
                                    <td class="budget-amount">2,500,000 ₫</td>
                                    <td class="spent-amount">800,000 ₫</td>
                                    <td class="remaining-amount">1,700,000 ₫</td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar" style="width: 32%; background-color: #7209b7;"></div>
                                        </div>
                                        <small class="text-muted">32%</small>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBudgetModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="category-icon" style="background-color: #f72585;">
                                                <i class="fas fa-heartbeat"></i>
                                            </div>
                                            <span class="category-name">Sức khỏe</span>
                                        </div>
                                    </td>
                                    <td class="budget-amount">2,500,000 ₫</td>
                                    <td class="spent-amount">500,000 ₫</td>
                                    <td class="remaining-amount">2,000,000 ₫</td>
                                    <td>
                                        <div class="progress-container">
                                            <div class="progress-bar" style="width: 20%; background-color: #f72585;"></div>
                                        </div>
                                        <small class="text-muted">20%</small>
                                    </td>
                                    <td class="action-buttons">
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editBudgetModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteBudgetModal">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include các modal từ file riêng -->
    <?php include 'modals/create.php'; ?>
    <?php include 'modals/edit.php'; ?>
    <?php include 'modals/delete.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Xử lý sự kiện khi modal hiển thị
        document.addEventListener('DOMContentLoaded', function() {
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
                deleteBudgetBtn.addEventListener('click', function() {
                    // Logic xóa ngân sách ở đây
                    alert('Ngân sách đã được xóa!');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('deleteBudgetModal'));
                    modal.hide();
                });
            }
            
            // Xử lý cho các nút sửa trong bảng
            const editButtons = document.querySelectorAll('.btn-outline-primary');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Logic để điền dữ liệu vào form sửa
                    // Ở đây bạn có thể lấy dữ liệu từ hàng tương ứng và điền vào form
                });
            });
        });
    </script>
</body>
</html>