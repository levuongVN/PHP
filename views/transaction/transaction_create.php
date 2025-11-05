<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
      </div>

      <!-- Main content -->
      <div class="col-md-9 col-lg-10 main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="m-0">Thêm giao dịch</h4>
          <a href="./transaction_index.php" class="btn btn-outline-secondary">
            <i class="fas fa-list"></i> Danh sách
          </a>
        </div>

        <form class="card p-4 shadow-sm" method="post" action="/PHP/handle/transaction_store.php">
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Ngày</label>
              <input type="date" name="transaction_date" class="form-control" 
                     value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="col-md-4">
              <label class="form-label">Danh mục</label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                <option value="1">Lương</option>
                <option value="2">Mua sắm</option>
                <option value="3">Ăn uống</option>
                <option value="4">Di chuyển</option>
                <option value="5">Giải trí</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">Loại</label>
              <select name="type" class="form-select" required>
                <option value="expense" <?= $type==='expense'?'selected':'' ?>>Chi</option>
                <option value="income" <?= $type==='income'?'selected':'' ?>>Thu</option>
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Số tiền</label>
              <input type="number" name="amount" step="1" class="form-control" 
                     placeholder="Nhập số tiền" required>
            </div>

            <div class="col-md-12">
              <label class="form-label">Ghi chú</label>
              <textarea name="description" rows="3" class="form-control" 
                        placeholder="Ví dụ: Bún bò, lương tháng 11 ..."></textarea>
            </div>

            <div class="col-12 d-flex gap-2 mt-3">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Lưu
              </button>
              <a class="btn btn-light" href="./transaction_index.php">
                <i class="fas fa-times me-1"></i> Hủy
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
