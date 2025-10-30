<!-- Modal sửa ngân sách -->
<div class="modal fade" id="editBudgetModal" tabindex="-1" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #4361ee, #3f37c9); color: white; border-radius: 15px 15px 0 0;">
                <h5 class="modal-title" id="editBudgetModalLabel">Sửa ngân sách</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editBudgetForm">
                    <div class="mb-3">
                        <label for="editCategorySelect" class="form-label">Danh mục</label>
                        <select class="form-select" id="editCategorySelect" required>
                            <option value="1" selected>Nhà ở</option>
                            <option value="2">Ăn uống</option>
                            <option value="3">Di chuyển</option>
                            <option value="4">Mua sắm</option>
                            <option value="5">Sức khỏe</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editBudgetAmount" class="form-label">Số tiền ngân sách</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="editBudgetAmount" value="5000000" required>
                            <span class="input-group-text">₫</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editBudgetPeriod" class="form-label">Thời gian</label>
                        <select class="form-select" id="editBudgetPeriod" required>
                            <option value="month" selected>Hàng tháng</option>
                            <option value="quarter">Hàng quý</option>
                            <option value="year">Hàng năm</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editBudgetNote" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea class="form-control" id="editBudgetNote" rows="2">Ngân sách cho thuê nhà và tiện ích</textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-radius: 0 0 15px 15px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Cập nhật</button>
            </div>
        </div>
    </div>
</div>