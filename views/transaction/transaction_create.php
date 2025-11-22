<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); //  Cần để đọc thông báo từ session
require_once(__DIR__ . "/../../functions/auth.php") ;
isLoggedIn();
$type = $_GET['type'] ?? 'expense';
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thêm giao dịch</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/sideBar.css">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3f37c9;
      --light: #f8f9fa;
      --dark: #212529;
    }

    body {
      background-color: #f5f7fb;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .main-content {
      margin-left: 250px;
      padding: 30px 40px;
    }

    .card {
      background: white;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .btn-primary {
      background-color: #4361ee;
      border-color: #4361ee;
    }

    .btn-primary:hover {
      background-color: #3f37c9;
      border-color: #3f37c9;
    }

    @media (max-width: 768px) {
      .main-content {
        margin-left: 0;
      }
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 sidebar p-0">
        <?php include '../sideBar.php'; ?>
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../functions/dbConnect.php';
require_once __DIR__ . '/../../functions/transaction.php';

$conn = getDbConnection();

if (!isset($_SESSION['user_id'])) {
    return;
}

$userId = (int) $_SESSION['user_id'];

$categories_income  = transaction_getCategories($conn, $userId, 'income');
$categories_expense = transaction_getCategories($conn, $userId, 'expense');
$allCategories      = array_merge($categories_income, $categories_expense);
?>

<!-- Modal thêm giao dịch -->
<div class="modal fade" id="createTransactionModal" tabindex="-1"
     aria-labelledby="createTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header modal-header-gradient">
        <h5 class="modal-title" id="createTransactionModalLabel">Thêm giao dịch mới</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form method="post" action="../../handle/transaction_store.php">
        <div class="modal-body">

          <div class="mb-3">
            <label class="form-label">Ngày</label>
            <input type="date" name="transaction_date" class="form-control"
                   value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Loại giao dịch</label>
            <select name="type" class="form-select" required>
              <option value="expense">Chi tiêu</option>
              <option value="income">Thu nhập</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" class="form-select" required>
              <option value="">-- Chọn danh mục --</option>
              <?php foreach ($allCategories as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Số tiền</label>
            <div class="input-group">
              <!-- dùng text + format dấu chấm -->
              <input type="text" name="amount"
                     class="form-control money-input"
                     placeholder="Nhập số tiền" required>
              <span class="input-group-text">₫</span>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="description" rows="3" class="form-control"
                      placeholder="Ví dụ: Ăn sáng, lương tháng 11 ..."></textarea>
          </div>

          <!-- báo cho handle biết là tạo mới & mở từ index -->
          <input type="hidden" name="action" value="create">
          <input type="hidden" name="from_index" value="1">

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu giao dịch</button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
  // Format tiền: tự thêm dấu . sau mỗi 3 số
  document.addEventListener('DOMContentLoaded', function () {
    const moneyInputs = document.querySelectorAll('.money-input');

    function formatCurrency(value) {
      // bỏ hết ký tự không phải số
      let v = value.replace(/\D/g, '');
      if (!v) return '';
      // chèn dấu . mỗi 3 số
      return v.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    moneyInputs.forEach(function (input) {
      input.addEventListener('input', function () {
        const cursorPos = this.selectionStart;
        const before = this.value;
        this.value = formatCurrency(this.value);

        // cố gắng giữ vị trí con trỏ tương đối (không hoàn hảo nhưng đủ dùng)
        const diff = this.value.length - before.length;
        this.setSelectionRange(cursorPos + diff, cursorPos + diff);
      });
    });
  });
</script>
