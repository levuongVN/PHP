<?php
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

// Lấy danh mục thu / chi để dùng trong select
$categories_income  = transaction_getCategories($conn, $userId, 'income');
$categories_expense = transaction_getCategories($conn, $userId, 'expense');
$allCategoriesEdit  = array_merge($categories_income, $categories_expense);
?>

<style>
  /* Dùng lại header gradient giống Thêm ngân sách / Thêm giao dịch */
  .modal-header-gradient {
      background: linear-gradient(
          90deg,
          rgba(42, 123, 155, 1) 0%,
          rgba(87, 121, 199, 1) 50%,
          rgba(237, 221, 83, 1) 100%
      );
      color: white;
      border-top-left-radius: 10px;
      border-top-right-radius: 10px;
  }
</style>

<!-- Modal SỬA giao dịch -->
<div class="modal fade" id="editTransactionModal" tabindex="-1"
     aria-labelledby="editTransactionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header modal-header-gradient">
        <h5 class="modal-title" id="editTransactionModalLabel">Sửa giao dịch</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <form method="post" action="../../handle/transaction_store.php">
        <div class="modal-body">

          <!-- báo cho handle biết đây là UPDATE -->
          <input type="hidden" name="action" value="update">
          <input type="hidden" name="id" id="editTransId">

          <div class="mb-3">
            <label class="form-label">Ngày</label>
            <input type="date" name="transaction_date" id="editTransDate"
                   class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Loại giao dịch</label>
            <select name="type" id="editTransType" class="form-select" required>
              <option value="expense">Chi tiêu</option>
              <option value="income">Thu nhập</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Danh mục</label>
            <select name="category_id" id="editTransCategory" class="form-select" required>
              <option value="">-- Chọn danh mục --</option>
              <?php foreach ($allCategoriesEdit as $cat): ?>
                <option value="<?= htmlspecialchars($cat['id']) ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Số tiền</label>
            <div class="input-group">
              <input type="text" name="amount" id="editTransAmount"
                     class="form-control"
                     placeholder="Nhập số tiền" required>
              <span class="input-group-text">₫</span>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label">Ghi chú</label>
            <textarea name="description" id="editTransDesc" rows="3"
                      class="form-control"
                      placeholder="Ví dụ: Ăn sáng, lương tháng 11 ..."></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
      </form>

    </div>
  </div>
</div>

<script>
  // format tiền: thêm dấu . mỗi 3 số
  function formatCurrencyInput(input) {
    let value = input.value.replace(/\D/g, '');
    if (!value) {
      input.value = '';
      return;
    }
    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
  }

  document.addEventListener('DOMContentLoaded', function() {
    const editModalEl = document.getElementById('editTransactionModal');
    const amountInput = document.getElementById('editTransAmount');

    if (amountInput) {
      amountInput.addEventListener('input', function () {
        formatCurrencyInput(this);
      });
    }

    if (!editModalEl) return;

    editModalEl.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      if (!button) return;

      const id       = button.getAttribute('data-id');
      const date     = button.getAttribute('data-date');
      const amount   = button.getAttribute('data-amount');   // số thô, ví dụ 150000
      const type     = button.getAttribute('data-type');
      const catId    = button.getAttribute('data-category-id');
      const desc     = button.getAttribute('data-description') || '';

      document.getElementById('editTransId').value    = id;
      document.getElementById('editTransDate').value  = date;
      document.getElementById('editTransType').value  = type;
      document.getElementById('editTransCategory').value = catId;

      if (amountInput) {
        const numeric = String(amount).replace(/[^\d]/g, '');
        amountInput.value = numeric ? Number(numeric).toLocaleString('vi-VN') : '';
      }

      document.getElementById('editTransDesc').value  = desc;
    });
  });
</script>
