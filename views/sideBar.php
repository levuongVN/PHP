<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Xác định base path tự động theo vị trí file hiện tại
$current_page = basename($_SERVER['PHP_SELF']);        // vd: transaction_index.php
$current_dir  = basename(dirname($_SERVER['PHP_SELF'])); // vd: transaction, statistic, views

// Ở gốc /views       -> '.'
// Ở các thư mục con  -> '..'
$base_path = ($current_dir === 'views') ? '.' : '..';

// Đánh dấu active cho nhóm "Giao dịch"
$transaction_pages = ['transaction_index.php', 'transaction_create.php', 'transaction_update.php'];
$is_transaction = in_array($current_page, $transaction_pages, true);
?>

<style>
  /* CSS cục bộ cho sidebar */
  .mm-sidebar {
    background: linear-gradient(135deg, #4361ee, #3f37c9);
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;        
    height: 100vh;
    padding-top: 20px;
    z-index: 1000;
  }
  .mm-sidebar .logo {
    padding: 0 20px 16px;
    text-align: center;
    border-bottom: 1px solid rgba(255,255,255,0.12);
  }
  .mm-sidebar .logo h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 700;
  }
  .mm-sidebar .nav {
    margin-top: 16px;
    padding: 0 10px 20px;
  }
  .mm-sidebar .nav-link {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,0.9);
    padding: 10px 12px;
    margin: 6px 0;
    border-radius: 8px;
    text-decoration: none;
    transition: background .25s ease;
  }
  .mm-sidebar .nav-link i {
    width: 22px;
    margin-right: 10px;
    text-align: center;
  }
  .mm-sidebar .nav-link:hover,
  .mm-sidebar .nav-link.active {
    background: rgba(255,255,255,0.15);
    color: #fff;
  }

  /* Đảm bảo nội dung chính né sidebar (nếu trang chưa có) */
  .main-content { margin-left: 250px; }
  @media (max-width: 768px) {
    .mm-sidebar { position: static; width: 100%; height: auto; }
    .main-content { margin-left: 0 !important; }
  }
</style>

<!-- Sidebar -->
<div class="mm-sidebar">
  <div class="logo">
    <h3><i class="fas fa-wallet"></i> MoneyMaster</h3>
  </div>

  <nav class="nav flex-column">
    <a class="nav-link <?= $current_page === 'Home.php' ? 'active' : '' ?>"
       href="<?= $base_path ?>/Home.php">
      <i class="fas fa-home"></i> Trang chủ
    </a>

    <a class="nav-link <?= $is_transaction ? 'active' : '' ?>"
       href="<?= $base_path ?>/transaction/transaction_index.php">
      <i class="fas fa-exchange-alt"></i> Giao dịch
    </a>

    <a class="nav-link <?= $current_page === 'statistic.php' ? 'active' : '' ?>"
       href="<?= $base_path ?>/statistic/statistic.php">
      <i class="fas fa-chart-pie"></i> Thống kê
    </a>

    <a class="nav-link <?= $current_page === 'budget.php' ? 'active' : '' ?>"
       href="<?= $base_path ?>/budget/budget.php">
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
