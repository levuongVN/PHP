<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
  header('Location: ../../Index.php');
  exit();
}

require_once __DIR__ . '/../../functions/dbConnect.php';
require_once __DIR__ . '/../../functions/transaction.php';

$conn = getDbConnection();
$userId = (int) $_SESSION['user_id'];
$full_name = $_SESSION['full_name'] ?? ($_SESSION['username'] ?? 'User');

function format_vnd($amount)
{
  return number_format((float) $amount, 0, ',', '.') . ' ₫';
}

// Lấy tất cả giao dịch của user
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
  if (!in_array($type, ['income', 'expense'])) {
    continue;
  }

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

  if ($type === 'income') {
    $totalIncome += $amount;
  } elseif ($type === 'expense') {
    $totalExpense += $amount;
  }
}

$currentBalance = $totalIncome - $totalExpense;
?>
<?php
require_once(__DIR__ . "/../../functions/auth.php") ;
isLoggedIn();
$theme = $_SESSION['theme'] ?? '';
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

    .user-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>

<body>
  <?php include '../sideBar.php'; ?>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-9 col-lg-10 main-content">
        <!-- HEADER -->
        <div class="header d-flex justify-content-between align-items-center">
          <div>
            <h2 class="mb-0">Giao dịch</h2>
            <p class="text-muted mb-0">Theo dõi và quản lý chi tiêu, thu nhập của bạn</p>
          </div>

          <div class="d-flex align-items-center" style="gap: 16px;">

            <!-- Nút mở modal thêm -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
              data-bs-target="#createTransactionModal">
              <i class="fas fa-plus me-1"></i> Thêm giao dịch
            </button>

            <!-- User dropdown -->
            <div class="dropdown">
              <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($full_name) ?>&background=4361ee&color=fff"
                  class="user-avatar me-2">
                <span><?= htmlspecialchars($full_name) ?></span>
              </a>

              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Cài đặt</a></li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li><a class="dropdown-item" href="../../handle/logout_process.php">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                  </a></li>
              </ul>
            </div>

          </div>
        </div>

        <!-- Tiêu đề khu vực tổng quan -->
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="m-0">Tổng quan giao dịch theo danh mục</h4>
        </div>

        <!-- Cards tổng quan -->
        <div class="row mb-4">
          <div class="col-md-3 mb-3">
            <div class="card summary-card bg-primary text-white">
              <div class="card-body">
                <h6 class="card-title"><i class="fas fa-arrow-down me-2"></i>Tổng thu</h6>
                <h4 class="mb-0"><?= format_vnd($totalIncome) ?></h4>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card summary-card bg-danger text-white">
              <div class="card-body">
                <h6 class="card-title"><i class="fas fa-arrow-up me-2"></i>Tổng chi</h6>
                <h4 class="mb-0"><?= format_vnd($totalExpense) ?></h4>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card summary-card bg-success text-white">
              <div class="card-body">
                <h6 class="card-title"><i class="fas fa-wallet me-2"></i>Số dư</h6>
                <h4 class="mb-0"><?= format_vnd($currentBalance) ?></h4>
              </div>
            </div>
          </div>
          <div class="col-md-3 mb-3">
            <div class="card summary-card bg-info text-white">
              <div class="card-body">
                <h6 class="card-title"><i class="fas fa-receipt me-2"></i>Tổng giao dịch</h6>
                <h4 class="mb-0"><?= $totalTransactions ?></h4>
              </div>
            </div>
          </div>
        </div>

        <!-- Tree View theo danh mục -->
        <div class="card p-4 shadow-sm">
          <h5 class="mb-3">Giao dịch</h5>

          <ul class="category-tree">
            <?php foreach (['income' => 'Thu nhập', 'expense' => 'Chi tiêu'] as $tKey => $tLabel): ?>
              <?php if (!empty($grouped[$tKey])): ?>
                <?php foreach ($grouped[$tKey] as $catName => $info): ?>
                  <?php
                  $isIncome = ($tKey === 'income');
                  $collapseId = 'cat-' . $tKey . '-' . md5($catName);
                  ?>
                  <li class="category-item">
                    <div class="category-header" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>"
                      aria-expanded="false">
                      <div class="d-flex justify-content-between align-items-center">
                        <div>
                          <span class="category-badge <?= $isIncome ? 'category-income' : 'category-expense' ?>">
                            <i class="<?= $isIncome ? 'fas fa-money-bill' : 'fas fa-receipt' ?>"></i>
                          </span>
                          <strong><?= htmlspecialchars($catName) ?></strong>
                          <small class="text-muted ms-2 count-transactions">
                            (<?= $info['count'] ?> giao dịch)
                          </small>
                        </div>
                        <div>
                          <span class="<?= $isIncome ? 'amount-income' : 'amount-expense' ?>">
                            <?= $isIncome ? '+' : '-' ?>       <?= format_vnd($info['total']) ?>
                          </span>
                          <i class="fas fa-chevron-down ms-2"></i>
                        </div>
                      </div>
                    </div>

                    <div id="<?= $collapseId ?>" class="collapse">
                      <div class="category-transactions">
                        <?php foreach ($info['transactions'] as $tran): ?>
                          <div class="transaction-item">
                            <div class="transaction-row">
                              <div class="flex-grow-1">
                                <div class="fw-medium">
                                  <?= htmlspecialchars($tran['description'] ?: ($tran['category_name'] ?? 'Giao dịch')) ?>
                                </div>
                                <small class="text-muted">
                                  <?= date('d/m/Y', strtotime($tran['transaction_date'])) ?>
                                </small>
                              </div>
                              <div class="text-end">
                                <div class="<?= $tran['type'] === 'income' ? 'amount-income' : 'amount-expense' ?>">
                                  <?= $tran['type'] === 'income' ? '+' : '-' ?>
                                  <?= format_vnd($tran['amount']) ?>
                                </div>
                                <div class="mt-2">
                                  <!-- Nút SỬA mở modal -->
                                  <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-transaction"
                                    data-bs-toggle="modal" data-bs-target="#editTransactionModal"
                                    data-id="<?= (int) $tran['id'] ?>"
                                    data-date="<?= htmlspecialchars($tran['transaction_date']) ?>"
                                    data-amount="<?= (float) $tran['amount'] ?>"
                                    data-type="<?= htmlspecialchars($tran['type']) ?>"
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
                          </div>
                        <?php endforeach; ?>
                      </div>
                    </div>
                  </li>
                <?php endforeach; ?>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal thêm giao dịch -->
  <?php include __DIR__ . '/transaction_create.php'; ?>
  <!-- Modal sửa giao dịch -->
  <?php include __DIR__ . '/transaction_edit.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>