<?php
require_once(__DIR__ . "/../../handle/reminder_process.php");
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];
$full_name = $_SESSION['full_name'];
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nhắc Nhở Ngân Sách - Quản Lý Tài Chính</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/reminder.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 col-xl-2 col-xxl-2 sidebar">
                <?php include '../sideBar.php' ?>
            </div>

            <div class="col-md-9 col-lg-10 main-content">
                <!-- Header -->
                <div class="header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Nhắc nhở</h2>
                        <p class="text-muted mb-0">Quản lý thông báo và cảnh báo ngân sách của bạn</p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Nút Thêm Nhắc Nhở Mới -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReminderModal">
                            <i class="fas fa-plus me-1"></i> Thêm nhắc nhở
                        </button>
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
                                <li><a class="dropdown-item" href="../../handle/logout_process.php"><i
                                            class="fas fa-sign-out-alt me-2"></i> Đăng xuất</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs nav-tabs-custom" id="reminderTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="budget-tab" data-bs-toggle="tab" data-bs-target="#budget"
                            type="button" role="tab">
                            <i class="fas fa-chart-pie me-2"></i>Ngân sách
                            <span class="badge-count"><?php echo count($budgetReminders); ?></span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="bills-tab" data-bs-toggle="tab" data-bs-target="#bills"
                            type="button" role="tab">
                            <i class="fas fa-file-invoice-dollar me-2"></i>Hóa đơn
                            <span class="badge-count"><?php echo count($billReminders); ?></span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="reminderTabsContent">

                    <!-- Tab Ngân sách -->
                    <div class="tab-pane fade show active" id="budget" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <?php if (empty($budgetReminders)): ?>
                                    <div class="empty-state">
                                        <i class="fas fa-chart-pie"></i>
                                        <h4>Không có cảnh báo ngân sách</h4>
                                        <p class="text-muted">Tất cả ngân sách của bạn đang trong tầm kiểm soát.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($budgetReminders as $reminder): ?>
                                        <?php
                                        $alert = getBudgetAlertType($reminder['percentage_spent']);
                                        $spentAmount = floatval($reminder['amount']) * floatval($reminder['percentage_spent']) / 100;
                                        ?>
                                        <div class="notification-card <?php echo $alert['type']; ?>">
                                            <div class="d-flex align-items-start">
                                                <div class="notification-icon <?php echo $alert['type']; ?>">
                                                    <i class="fas fa-<?php echo $alert['icon']; ?>"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="notification-title">
                                                            <?php echo $alert['message']; ?> -
                                                            <?php echo htmlspecialchars($reminder['name']); ?>
                                                        </div>
                                                        <div class="action-buttons">
                                                            <button class="btn btn-sm btn-outline-primary me-1"
                                                                data-bs-toggle="modal" data-bs-target="#editReminderModal"
                                                                data-id="<?php echo $reminder['id']; ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                                                data-bs-target="#deleteReminderModal"
                                                                data-id="<?php echo $reminder['id']; ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="notification-message">
                                                        Bạn đã chi tiêu <?php echo formatCurrency($spentAmount); ?>
                                                        (<?php echo $reminder['percentage_spent']; ?>%) so với ngân sách
                                                        <?php echo formatCurrency($reminder['amount']); ?>.
                                                    </div>
                                                    <div class="notification-meta">
                                                        <i class="fas fa-clock me-1"></i> Hôm nay •
                                                        <i class="fas fa-tag me-1"></i>
                                                        <?php echo htmlspecialchars($reminder['name']); ?>
                                                    </div>
                                                    <div class="notification-actions">
                                                        <button
                                                            class="btn btn-outline-<?php echo $alert['type']; ?> btn-sm me-2">
                                                            <i class="fas fa-chart-pie me-1"></i> Xem chi tiết
                                                        </button>
                                                        <button class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-times me-1"></i> Bỏ qua
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Hóa đơn -->
                    <div class="tab-pane fade" id="bills" role="tabpanel">
                        <div class="row">
                            <div class="col-12">
                                <?php if (empty($billReminders)): ?>
                                    <div class="empty-state">
                                        <i class="fas fa-file-invoice-dollar"></i>
                                        <h4>Không có hóa đơn sắp đến hạn</h4>
                                        <p class="text-muted">Tất cả hóa đơn của bạn đã được thanh toán.</p>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($billReminders as $reminder): ?>
                                        <?php $status = getBillStatus($reminder['due_date']); ?>
                                        <div class="notification-card <?php echo $status['type']; ?>">
                                            <div class="d-flex align-items-start">
                                                <div class="notification-icon bill-icon <?php echo $status['type']; ?>">
                                                    <i class="fas fa-file-invoice-dollar" style="color: white;"></i>
                                                </div>
                                                <div class="notification-content">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <div class="notification-title">Hóa đơn
                                                            <?php echo htmlspecialchars($reminder['name']); ?>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="bill-amount"><?php echo formatCurrency($reminder['amount']); ?></span>
                                                            <div class="action-buttons ms-2 d-inline-block">
                                                                <button class="btn btn-sm btn-outline-primary me-1"
                                                                    data-bs-toggle="modal" data-bs-target="#editReminderModal"
                                                                    data-id="<?php echo $reminder['id']; ?>">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    data-bs-toggle="modal" data-bs-target="#deleteReminderModal"
                                                                    data-id="<?php echo $reminder['id']; ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="notification-message">
                                                        <div class="due-date-info">
                                                            <i class="fas fa-calendar-day me-2"></i>
                                                            Đến hạn:
                                                            <strong><?php echo date('d/m/Y', strtotime($reminder['due_date'])); ?></strong>
                                                        </div>
                                                        <div class="alert-status text-<?php echo $status['type']; ?>">
                                                            <i class="fas fa-<?php echo $status['icon']; ?> me-1"></i>
                                                            <?php echo $status['message']; ?>
                                                        </div>
                                                    </div>
                                                    <div class="notification-actions">
                                                        <button class="btn btn-outline-secondary btn-sm me-2">
                                                            <i class="fas fa-calendar-plus me-1"></i> Lên lịch
                                                        </button>
                                                        <button class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-check me-1"></i> Đã thanh toán
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include './modals/create.php'; ?>
    <?php include './modals/edit.php'; ?>
    <?php include './modals/delete.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>