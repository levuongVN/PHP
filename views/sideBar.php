<?php
// Xác định base path tự động
$base_path = (basename(dirname($_SERVER['PHP_SELF'])) == 'statistic') ? '..' : '.';
?>
<!-- Sidebar -->
<div class="col-md-3 col-lg-2 sidebar">
    <div class="logo">
        <h3><i class="fas fa-wallet"></i> MoneyMaster</h3>
    </div>
    <nav class="nav flex-column mt-4">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'Home.php' ? 'active' : '' ?>" href="<?= $base_path ?>/Home.php">
            <i class="fas fa-home"></i> Trang chủ
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-exchange-alt"></i> Giao dịch
        </a>
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'statistic.php' ? 'active' : '' ?>" href="<?= $base_path ?>/statistic/statistic.php">
            <i class="fas fa-chart-pie"></i> Thống kê
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-tags"></i> Danh mục
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-chart-line"></i> Ngân sách
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-bell"></i> Nhắc nhở
        </a>
        <a class="nav-link" href="#">
            <i class="fas fa-cog"></i> Cài đặt
        </a>
    </nav>
</div>