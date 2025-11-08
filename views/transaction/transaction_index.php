<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tổng quan giao dịch theo danh mục</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../../css/transaction.css">
</head>

<body>
  <div>
    <?php include '../sideBar.php' ?>
  </div>

  <div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="m-0">Tổng quan giao dịch theo danh mục</h4>
      <a class="btn btn-primary" href="#">
        <i class="fas fa-plus me-1"></i> Thêm giao dịch
      </a>
    </div>

    <!-- Cards tổng quan -->
    <div class="row mb-4">
      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-primary text-white">
          <div class="card-body">
            <h6 class="card-title"><i class="fas fa-arrow-down me-2"></i>Tổng thu</h6>
            <h4 class="mb-0">15.250.000 ₫</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-danger text-white">
          <div class="card-body">
            <h6 class="card-title"><i class="fas fa-arrow-up me-2"></i>Tổng chi</h6>
            <h4 class="mb-0">8.180.000 ₫</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-success text-white">
          <div class="card-body">
            <h6 class="card-title"><i class="fas fa-wallet me-2"></i>Số dư</h6>
            <h4 class="mb-0">7.070.000 ₫</h4>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-3">
        <div class="card summary-card bg-info text-white">
          <div class="card-body">
            <h6 class="card-title"><i class="fas fa-receipt me-2"></i>Tổng giao dịch</h6>
            <h4 class="mb-0">24</h4>
          </div>
        </div>
      </div>
    </div>

    <!-- Tree View theo danh mục -->
    <div class="card p-4 shadow-sm">
      <h5 class="mb-3">Giao dịch </h5>

      <ul class="category-tree">
        <!-- Danh mục cha - Thu nhập -->
        <li class="category-item">
          <div class="category-header" data-bs-toggle="collapse" data-bs-target="#category-income" aria-expanded="true">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="category-badge category-income">
                  <i class="fas fa-money-bill"></i>
                </span> <strong>Lương</strong>
                <small class="text-muted ms-2 count-transactions">
                  (8 giao dịch)
                </small>
              </div>
              <div>
                <span class="amount-income">
                  +15.250.000 ₫
                </span>
                <i class="fas fa-chevron-down ms-2"></i>
              </div>
            </div>
          </div>

          <div class="collapse show" id="category-income">
            <div class="transactions-list">
              <!-- Giao dịch của danh mục cha -->
              <div class="transaction-row">
                <div class="flex-grow-1">
                  <div class="fw-medium">Lương tháng 12</div>
                  <small class="text-muted">05/12/2023</small>
                </div>
                <div class="text-end">
                  <div class="amount-income">
                    +12.000.000 ₫
                  </div>
                  <div class="mt-2">
                    <a href="#" class="btn btn-sm btn-outline-secondary">Sửa</a>
                    <a href="#" class="btn btn-sm btn-outline-danger">Xoá</a>
                  </div>
                </div>
              </div>
              <div class="transaction-row">
                <div class="flex-grow-1">
                  <div class="fw-medium">Thưởng dự án</div>
                  <small class="text-muted">15/12/2023</small>
                </div>
                <div class="text-end">
                  <div class="amount-income">
                    +3.000.000 ₫
                  </div>
                  <div class="mt-2">
                    <a href="#" class="btn btn-sm btn-outline-secondary">Sửa</a>
                    <a href="#" class="btn btn-sm btn-outline-danger">Xoá</a>
                  </div>
                </div>
              </div>
              <div class="transaction-row">
                <div class="flex-grow-1">
                  <div class="fw-medium">Tiền lì xì</div>
                  <small class="text-muted">20/12/2023</small>
                </div>
                <div class="text-end">
                  <div class="amount-income">
                    +250.000 ₫
                  </div>
                  <div class="mt-2">
                    <a href="#" class="btn btn-sm btn-outline-secondary">Sửa</a>
                    <a href="#" class="btn btn-sm btn-outline-danger">Xoá</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>

        <!-- Danh mục cha - Chi tiêu -->
        <li class="category-item">
          <div class="category-header" data-bs-toggle="collapse" data-bs-target="#category-expense"
            aria-expanded="false">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="category-badge category-expense">
                  <i class="fas fa-money-bill-wave"></i>
                </span>
                <strong>Chi tiêu</strong>
                <small class="text-muted ms-2 count-transactions">
                  (16 giao dịch)
                </small>
              </div>
              <div>
                <span class="amount-expense">
                  -8.180.000 ₫
                </span>
                <i class="fas fa-chevron-down ms-2"></i>
              </div>
            </div>
          </div>

          <div class="collapse" id="category-expense">
            <div class="transactions-list">
              <!-- Giao dịch của danh mục cha -->
              <div class="transaction-row">
                <div class="flex-grow-1">
                  <div class="fw-medium">Tiền thuê nhà</div>
                  <small class="text-muted">01/12/2023</small>
                </div>
                <div class="text-end">
                  <div class="amount-expense">
                    -3.500.000 ₫
                  </div>
                  <div class="mt-2">
                    <a href="#" class="btn btn-sm btn-outline-secondary">Sửa</a>
                    <a href="#" class="btn btn-sm btn-outline-danger">Xoá</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>