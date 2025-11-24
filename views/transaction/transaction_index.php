<?php
session_start();
// ========== PHẦN TỪ HEAD ==========
require_once(__DIR__ . "/../../functions/auth.php");
require_once __DIR__ . '/../../functions/dbConnect.php';
require_once __DIR__ . '/../../functions/transaction.php';
isLoggedIn();
$theme = $_SESSION['theme'] ?? '';
// ========== PHẦN TỪ NHÁNH transaction ==========
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['user_id'])) {
  header('Location: ../../Index.php');
  exit();
}
$conn = getDbConnection();
$userId = (int) $_SESSION['user_id'];
$full_name = $_SESSION['full_name'] ?? ($_SESSION['username'] ?? 'User');

function format_vnd($amount)
{
  return number_format((float) $amount, 0, ',', '.') . ' ₫';
}

$transactions = transaction_get_all($conn, $userId);

$totalIncome = 0;
$totalExpense = 0;
$totalTransactions = count($transactions);

$grouped = [
  'income' => [],
  'expense' => [],
];

foreach ($transactions as $row) {
  $type = $row['type'];

  if (!in_array($type, ['income', 'expense']))
    continue;

  $catName = $row['category_name'] ?? 'Khác';

  if (!isset($grouped[$type][$catName])) {
    $grouped[$type][$catName] = [
      'total' => 0,
      'count' => 0,
      'transactions' => [],
    ];
  }

  $amount = (float) $row['amount'];

  $grouped[$type][$catName]['total'] += $amount;
  $grouped[$type][$catName]['count'] += 1;
  $grouped[$type][$catName]['transactions'][] = $row;

  if ($type === 'income')
    $totalIncome += $amount;
  elseif ($type === 'expense')
    $totalExpense += $amount;
}

$currentBalance = $totalIncome - $totalExpense;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MoneyMaster - Giao dịch</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <link rel="stylesheet" href="../../css/transaction.css">
  <link rel="stylesheet" href="../../css/sideBar.css">

  <style>
    :root {
      --primary: #4361ee;
      --danger: #f72585;
      --success: #4cc9f0;
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
    }
  </style>
</head>

<body style="background: <?= $theme ?>">
  <?php include '../sideBar.php'; ?>
  <div class="main-content">
    <!-- Header -->
    <div class="header d-flex justify-content-between align-items-center">
      <div>
        <h2 class="mb-0">Quản lí giao dịch</h2>
        <p class="text-muted mb-0">Tổng quan giao dịch theo danh mục</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <!-- Nút Thêm Nhắc Nhở Mới -->
        <button class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#createTransactionModal">
          <i class="fas fa-plus me-1"></i> Thêm giao dịch
        </button>
        <div class="dropdown">
          <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
            <img src="https://ui-avatars.com/api/?name=Nguyen+Van+A&background=4361ee&color=fff"
              class="user-avatar me-2">
            <span><?php echo htmlspecialchars($full_name); ?></span>
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="../profile/profile.php"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="../../handle/logout_process.php"><i class="fas fa-sign-out-alt me-2"></i>
                Đăng xuất</a></li>
          </ul>
        </div>
      </div>
    </div>
    
    <!-- Summary -->
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-primary text-white">
          <div class="card-body">
            <h6 class="card-title">Tổng thu</h6>
            <h4><?= format_vnd($totalIncome) ?></h4>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-danger text-white">
          <div class="card-body">
            <h6 class="card-title">Tổng chi</h6>
            <h4><?= format_vnd($totalExpense) ?></h4>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-success text-white">
          <div class="card-body">
            <h6 class="card-title">Số dư</h6>
            <h4><?= format_vnd($currentBalance) ?></h4>
          </div>
        </div>
      </div>

      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-info text-white">
          <div class="card-body">
            <h6 class="card-title">Tổng giao dịch</h6>
            <h4><?= $totalTransactions ?></h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Category Tree -->
    <div class="card p-4 shadow-sm">
      <h5 class="mb-3">Giao dịch</h5>

      <ul class="category-tree">

        <?php foreach (['income' => 'Thu nhập', 'expense' => 'Chi tiêu'] as $key => $label): ?>
          <?php foreach ($grouped[$key] as $catName => $info): ?>
            <?php $collapseId = "cat-" . $key . "-" . md5($catName); ?>

            <li class="category-item">
              <div class="category-header" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">

                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <strong><?= $catName ?></strong>
                    <small>(<?= $info['count'] ?> giao dịch)</small>
                  </div>

                  <div>
                    <span class="<?= $key === 'income' ? 'text-success' : 'text-danger' ?>">
                      <?= $key === 'income' ? '+' : '-' ?>     <?= format_vnd($info['total']) ?>
                    </span>
                    <i class="fas fa-chevron-down ms-2"></i>
                  </div>
                </div>

              </div>

              <div id="<?= $collapseId ?>" class="collapse">
                <?php foreach ($info['transactions'] as $tran): ?>
                  <div class="transaction-row">
                    <div class="flex-grow-1">
                      <div class="fw-bold"><?= htmlspecialchars($tran['description'] ?? '') ?></div>
                      <small><?= date('d/m/Y', strtotime($tran['transaction_date'])) ?></small>
                    </div>

                    <div class="text-end">
                      <div class="<?= $tran['type'] === 'income' ? 'text-success' : 'text-danger' ?>">
                        <?= $tran['type'] === 'income' ? '+' : '-' ?>
                        <?= format_vnd($tran['amount']) ?>
                      </div>
                      <div class="mt-2">
                        <!-- Nút SỬA mở modal -->
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-transaction"
                          data-bs-toggle="modal" data-bs-target="#editTransactionModal" data-id="<?= (int) $tran['id'] ?>"
                          data-date="<?= htmlspecialchars($tran['transaction_date']) ?>"
                          data-amount="<?= (float) $tran['amount'] ?>" data-type="<?= htmlspecialchars($tran['type']) ?>"
                          data-category-id="<?= (int) $tran['category_id'] ?>"
                          data-description="<?= htmlspecialchars($tran['description'] ?? '', ENT_QUOTES) ?>">
                          Sửa
                        </button>
                        <!-- Nút XOÁ: gửi POST tới transaction_store.php -->
                        <form method="post" action="../../handle/transaction_store.php" class="d-inline"
                          onsubmit="return confirm('Bạn có chắc muốn xoá giao dịch này không?');">
                          <input type="hidden" name="action" value="delete">
                          <input type="hidden" name="id" value="<?= (int) $tran['id'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-danger">
                            Xoá
                          </button>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php endforeach ?>
              </div>
            </li>

          <?php endforeach ?>
        <?php endforeach ?>

      </ul>
    </div>

  </div>

  <?php include './transaction_create.php'; ?>
  <?php include './transaction_edit.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>