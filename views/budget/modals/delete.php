<?php
require_once(__DIR__ . "/../../../functions/auth.php") ;
isLoggedIn();
?>
<!-- Modal xóa ngân sách -->
<div class="modal fade" id="deleteBudgetModal" tabindex="-1" aria-labelledby="deleteBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(90deg,rgba(42, 123, 155, 1) 0%, rgba(87, 121, 199, 1) 50%, rgba(237, 221, 83, 1) 100%); color: white;">
                <h5 class="modal-title" id="deleteBudgetModalLabel">Xóa ngân sách</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="deleteBudgetForm" method="post" action="../../handle/budget_process.php">
                    <input type="hidden" name="action" value="delete_budget">
                    <input type="hidden" id="delete_budget_id" name="delete_budget_id">
                    <input type="hidden" id="delete_cate_id" name="delete_cate_id">
                    
                    <p>Bạn có chắc chắn muốn xóa ngân sách này không?</p>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Hành động này không thể hoàn tác. Tất cả dữ liệu liên quan đến ngân sách và danh mục sẽ bị xóa.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="submit" form="deleteBudgetForm" class="btn btn-danger">Xóa ngân sách</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteBudgetModal');
    
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const budgetId = button.getAttribute('data-budget-id');
        const cateId = button.getAttribute('data-cate-id'); 
        document.getElementById('delete_budget_id').value = budgetId;
        document.getElementById('delete_cate_id').value = cateId;
    });
});
</script>