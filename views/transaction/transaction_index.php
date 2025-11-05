<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

require_once '../../functions/dbConnect.php';
require_once '../../functions/transaction.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header('Location: ../../Index.php');
    exit();
}

$conn    = getDbConnection();
$user_id = (int)$_SESSION['user_id'];

// Lấy danh sách giao dịch từ DB
$transactions = transaction_get_all($conn, $user_id);

// Helper: encode an toàn (tránh null -> deprecated)
function h($v) {
    return htmlspecialchars((string)($v ?? ''), ENT_QUOTES, 'UTF-8');
}

// Định dạng tiền VND
function vnd($n) {
    $n = (float)($n ?? 0);
    $p = $n < 0 ? '-' : '';
    return $p . number_format(abs($n), 0, ',', '.') . ' ₫';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Danh sách giao dịch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    :root { --primary:#4361ee; }
    body { background:#f5f7fb; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; }
    /* GIỐNG layout chính: sidebar đứng riêng, nội dung né 250px */
    .main-content { margin-left: 250px; padding: 24px 28px; }
    .card { border:1px solid #eee; border-radius:14px; background:#fff; }
    .pill{ padding:.25rem .6rem; border-radius:999px; font-size:.85rem; background:#f1f3f5; }
    .pill.income{ background:#e9f8ef; color:#18794e; }
    .pill.expense{ background:#fff1f3; color:#c92a2a; }
    table.table td, table.table th{ vertical-align: middle; }
    .text-nowrap { white-space: nowrap; }
    @media (max-width: 768px) { .main-content{ margin-left:0; } }
  </style>
</head>
<body>
  <?php include '../sideBar.php'; ?>  <!-- Include sidebar đúng kiểu layout chính -->

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h4 class="m-0">Danh sách giao dịch</h4>
      <a class="btn btn-primary" href="./transaction_create.php">
        <i class="fas fa-plus me-1"></i> Thêm giao dịch
      </a>
    </div>

    <div class="card p-3 shadow-sm">
      <div class="table-responsive">
        <table class="table align-middle">
          <thead class="table-light">
            <tr>
              <th>Ngày</th>
              <th>Danh mục</th>
              <th>Loại</th>
              <th class="text-nowrap">Số tiền</th>
              <th>Ghi chú</th>
              <th style="width:1%"></th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($transactions)): ?>
              <tr>
                <td colspan="6" class="text-center text-muted py-4">
                  <em>Chưa có giao dịch.</em>
                </td>
              </tr>
            <?php else: foreach ($transactions as $t): ?>
              <tr>
                <td><?= h($t['transaction_date']) ?></td>
                <td><?= h($t['category_name'] ?? 'Không rõ') ?></td>
                <td>
                  <?php if (($t['type'] ?? '') === 'income'): ?>
                    <span class="pill income">Thu</span>
                  <?php else: ?>
                    <span class="pill expense">Chi</span>
                  <?php endif; ?>
                </td>
                <td class="text-nowrap"><strong><?= vnd($t['amount']) ?></strong></td>
                <td><?= h($t['description'] ?? '') ?></td>
                <td class="text-nowrap">
                  <a class="me-2" href="./transaction_update.php?id=<?= urlencode($t['id']) ?>">Sửa</a>
                  <a class="text-danger" href="../../handle/transaction_delete.php?id=<?= urlencode($t['id']) ?>"
                     onclick="return confirm('Xoá giao dịch này?')">Xoá</a>
                </td>
              </tr>
            <?php endforeach; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div><!-- /.main-content -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
