<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../../functions/auth.php") ;
isLoggedIn();
// Lấy giá trị lỗi từ session và gán cho biến cục bộ, sau đó xóa chúng khỏi session
$has_error = false;
$error_category = null;
$error_budget_amount = null;

if (isset($_SESSION['error_category']) || isset($_SESSION['error_budget_amount'])) {
    $has_error = true;
    $error_category = $_SESSION['error_category'] ?? null;
    $error_budget_amount = $_SESSION['error_budget_amount'] ?? null;
    unset($_SESSION['error_category']);
    unset($_SESSION['error_budget_amount']);
}
?>
<!-- Modal thêm ngân sách -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"
                style="background: linear-gradient(90deg,rgba(42, 123, 155, 1) 0%, rgba(87, 121, 199, 1) 50%, rgba(237, 221, 83, 1) 100%); color: white;">
                <h5 class="modal-title" id="addBudgetModalLabel">Thêm ngân sách mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBudgetForm" method="post" action="../../handle/budget_process.php">
                    <input type="hidden" name="action" value="create_budget">
                    <div class="mb-3">
                        <label for="categorySelect" class="form-label">Danh mục</label>
                        <input type="text" class="form-control" id="categories" name="category"
                            placeholder="Nhập danh mục"
                            value="<?= isset($_SESSION['old_category']) ? htmlspecialchars($_SESSION['old_category']) : '' ?>" />
                        <?php if ($error_category): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error_category ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="mb-3">
                        <label for="categoryType" class="form-label">Loại danh mục</label>
                        <select class="form-select" id="categoryType" name="category_type">
                            <option value="expense" selected>Chi tiêu</option>
                            <option value="income">Thu nhập</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="budgetAmount" class="form-label">Số tiền ngân sách</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="budgetAmount" name="budget_amount"
                                placeholder="Nhập số tiền"
                                value="<?= isset($_SESSION['old_budget_amount']) ? htmlspecialchars($_SESSION['old_budget_amount']) : '' ?>">
                            <span class="input-group-text">₫</span>
                        </div>
                        <?php if ($error_budget_amount): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error_budget_amount ?>
                            </div>
                        <?php endif; ?>
                        <div class="invalid-feedback" id="budgetAmountError"></div>
                    </div>
                    <div class="mb-3">
                        <label for="budgetDate" class="form-label">Thời gian (YYYY-MM-DD)</label>
                        <input type="date" class="form-control" id="budgetDate" name="budget_date"
                            pattern="\d{4}-\d{2}-\d{2}" title="Vui lòng nhập ngày theo định dạng YYYY-MM-DD">
                        <div class="invalid-feedback" id="budgetDateError"></div>
                    </div>
                    <div class="modal-footer" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu ngân sách</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Định dạng số tiền với dấu phân cách hàng nghìn ngay khi đang nhập
    document.addEventListener('DOMContentLoaded', function () {
        const budgetAmountInput = document.getElementById('budgetAmount');

        if (budgetAmountInput) {
            function formatNumberWithCommas(value) {
                const numbersOnly = value.replace(/\D/g, '');

                if (numbersOnly) {
                    return parseInt(numbersOnly, 10).toLocaleString('vi-VN');
                }
                return '';
            }

            budgetAmountInput.addEventListener('input', function (e) {
                this.value = formatNumberWithCommas(this.value);
            });
        }

        // Đặt ngày mặc định là ngày hiện tại
        const budgetDateInput = document.getElementById('budgetDate');
        if (budgetDateInput) {
            const today = new Date();
            const yyyy = today.getFullYear();
            const mm = String(today.getMonth() + 1).padStart(2, '0');
            const dd = String(today.getDate()).padStart(2, '0');
            budgetDateInput.value = `${yyyy}-${mm}-${dd}`;
        }

        // Mở modal nếu có lỗi
        <?php if ($has_error): ?>
            var addBudgetModal = new bootstrap.Modal(document.getElementById('addBudgetModal'));
            addBudgetModal.show();
        <?php endif; ?>
    });
</script>