<!-- Modal thêm ngân sách -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(90deg,rgba(42, 123, 155, 1) 0%, rgba(87, 121, 199, 1) 50%, rgba(237, 221, 83, 1) 100%); color: white;">
                <h5 class="modal-title" id="addBudgetModalLabel">Thêm ngân sách mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addBudgetForm">
                    <div class="mb-3">
                        <label for="categorySelect" class="form-label">Danh mục</label>
                        <select class="form-select" id="categorySelect" required>
                            <option value="" selected disabled>Chọn danh mục</option>
                            <option value="1">Nhà ở</option>
                            <option value="2">Ăn uống</option>
                            <option value="3">Di chuyển</option>
                            <option value="4">Mua sắm</option>
                            <option value="5">Sức khỏe</option>
                            <option value="6">Giải trí</option>
                            <option value="7">Giáo dục</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="budgetAmount" class="form-label">Số tiền ngân sách</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="budgetAmount" placeholder="Nhập số tiền" required>
                            <span class="input-group-text">₫</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="budgetPeriod" class="form-label">Thời gian</label>
                        <select class="form-select" id="budgetPeriod" required>
                            <option value="month" selected>Hàng tháng</option>
                            <option value="quarter">Hàng quý</option>
                            <option value="year">Hàng năm</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="budgetNote" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea class="form-control" id="budgetNote" rows="2" placeholder="Thêm ghi chú về ngân sách này"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer" style="border-radius: 0 0 15px 15px;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Lưu ngân sách</button>
            </div>
        </div>
    </div>
</div>