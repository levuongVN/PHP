<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lấy giá trị lỗi từ session và gán cho biến cục bộ, sau đó xóa chúng khỏi session
$has_edit_error = false;
$edit_error_category = null;
$edit_error_budget_amount = null;

if (isset($_SESSION['edit_error_category']) || isset($_SESSION['edit_error_budget_amount'])) {
    $has_edit_error = true;
    $edit_error_category = $_SESSION['edit_error_category'] ?? null;
    $edit_error_budget_amount = $_SESSION['edit_error_budget_amount'] ?? null;
    unset($_SESSION['edit_error_category']);
    unset($_SESSION['edit_error_budget_amount']);
}
$categories = $categories_for_edit ?? [];
?>
<!-- Modal sửa ngân sách -->
<div class="modal fade" id="editBudgetModal" tabindex="-1" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"
                style="background: linear-gradient(90deg, rgba(155, 89, 182, 1) 0%, rgba(87, 121, 199, 1) 50%, rgba(52, 152, 219, 1) 100%); color: white;">
                <h5 class="modal-title" id="editBudgetModalLabel">Chỉnh sửa ngân sách</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBudgetForm" method="post" action="../../handle/budget_process.php">
                    <input type="hidden" name="action" value="update_budget">
                    <input type="hidden" name="budget_id" id="editBudgetId" value="">
                    
                    <div class="mb-3">
                        <label for="editCategorySelect" class="form-label">Danh mục</label>
                         <select class="form-select" id="editCategorySelect" name="category_id">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>">
                                    <?= htmlspecialchars($category['name']) ?> 
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="categoryType" class="form-label">Loại danh mục</label>
                        <select class="form-select" id="categoryType" name="category_type">
                            <option value="expense" selected>Chi tiêu</option>
                            <option value="income">Thu nhập</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editBudgetAmount" class="form-label">Số tiền ngân sách</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="editBudgetAmount" name="budget_amount"
                                placeholder="Nhập số tiền" value="">
                            <span class="input-group-text">₫</span>
                        </div>
                        <?php if ($edit_error_budget_amount): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $edit_error_budget_amount ?>
                            </div>
                        <?php endif; ?>
                        <div class="invalid-feedback" id="editBudgetAmountError"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="editBudgetDate" class="form-label">Thời gian (YYYY-MM-DD)</label>
                        <input type="date" class="form-control" id="editBudgetDate" name="budget_date"
                            pattern="\d{4}-\d{2}-\d{2}" title="Vui lòng nhập ngày theo định dạng YYYY-MM-DD" value="">
                        <div class="invalid-feedback" id="editBudgetDateError"></div>
                    </div>
                    
                    <div class="modal-footer" style="border-radius: 0 0 15px 15px;">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật ngân sách</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Định dạng số tiền với dấu phân cách hàng nghìn ngay khi đang nhập (cho edit form)
    document.addEventListener('DOMContentLoaded', function () {
        const editBudgetAmountInput = document.getElementById('editBudgetAmount');

        if (editBudgetAmountInput) {
            function formatNumberWithCommas(value) {
                const numbersOnly = value.replace(/\D/g, '');

                if (numbersOnly) {
                    return parseInt(numbersOnly, 10).toLocaleString('vi-VN');
                }
                return '';
            }

            editBudgetAmountInput.addEventListener('input', function (e) {
                this.value = formatNumberWithCommas(this.value);
            });
        }

        // Mở modal edit nếu có lỗi
        <?php if ($has_edit_error): ?>
            var editBudgetModal = new bootstrap.Modal(document.getElementById('editBudgetModal'));
            editBudgetModal.show();
        <?php endif; ?>

        // Xử lý khi modal edit được mở từ nút edit
        var editBudgetModalElement = document.getElementById('editBudgetModal');
        editBudgetModalElement.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Nút đã kích hoạt modal
            
            // Lấy dữ liệu từ các data attributes
            var budgetId = button.getAttribute('data-budget-id');
            var categoryId = button.getAttribute('data-category-id');
            var budgetAmount = button.getAttribute('data-value-amount');
            var month = button.getAttribute('data-month-budget');

            // console.log('DEBUG - Data from button:', {
            //     budgetId: budgetId,
            //     categoryId: categoryId,
            //     budgetAmount: budgetAmount,
            //     month: month
            // });

            // Điền dữ liệu vào form
            document.getElementById('editBudgetId').value = budgetId || '';
            
            // QUAN TRỌNG: Set giá trị cho select để auto-select category
            const categorySelect = document.getElementById('editCategorySelect');
            if (categorySelect && categoryId) {
                categorySelect.value = categoryId;
            }
            
            document.getElementById('editBudgetAmount').value = budgetAmount || '';
            
            if (month) {
                document.getElementById('editBudgetDate').value = month;
            } else {
                const today = new Date();
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                document.getElementById('editBudgetDate').value = `${yyyy}-${mm}-${dd}`;
            }
        });
    });
</script>