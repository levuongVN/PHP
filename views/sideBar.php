<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . "/../functions/auth.php") ;
isLoggedIn();
// Xác định base path tự động theo vị trí file hiện tại
$current_page = basename($_SERVER['PHP_SELF']);        // vd: transaction_index.php
$current_dir = basename(dirname($_SERVER['PHP_SELF'])); // vd: transaction, statistic, views

$base_path = ($current_dir === 'views') ? '.' : '..';


$hasNotifications = $_SESSION['has_notifications'] ?? false;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/sideBar.css">
</head>
<body>
  <div class="mm-sidebar">
    <div class="logo">
      <h3><i class="fas fa-wallet"></i> MoneyMaster</h3>
    </div>

    <nav class="nav flex-column">
      <a class="nav-link <?= $current_page === 'Home.php' ? 'active' : '' ?>" href="<?= $base_path ?>/Home.php">
        <i class="fas fa-home"></i> Trang chủ
      </a>

      <a class="nav-link <?= $current_page === 'transaction_index.php' ? 'active' : '' ?>"
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

      <a class="nav-link position-relative <?= $current_page === 'reminder.php' ? 'active' : '' ?>"
        href="<?= $base_path ?>/reminder/reminder.php">
        <i class="fas fa-bell"></i> Nhắc nhở
        <?php if ($hasNotifications): ?>
          <span
            class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
          </span>
        <?php endif ?>
      </a>
    </nav>
  </div>
</body>
</html>