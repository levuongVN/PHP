<div class="modal fade" id="editReminderModal" tabindex="-1" aria-labelledby="editReminderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editReminderModalLabel">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa nhắc nhở
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editReminderForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editReminderType" class="form-label">Loại nhắc nhở</label>
                                <select class="form-select" id="editReminderType" name="editReminderType">
                                    <option value="budget">Cảnh báo ngân sách</option>
                                    <option value="bill">Hóa đơn</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editReminderName" class="form-label">Tên nhắc nhở</label>
                                <input type="text" class="form-control" id="editReminderName"
                                    name="editReminderName">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="editReminderAmount" class="form-label">Số tiền</label>
                                <input type="number" class="form-control" id="editReminderAmount"
                                    name="editReminderAmount">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="editPercentageField">
                                <label for="editReminderPercentage" class="form-label">Phần trăm cảnh báo
                                    (%)</label>
                                <input type="number" class="form-control" id="editReminderPercentage"
                                    name="editReminderPercentage" min="0" max="200">
                            </div>
                            <div class="mb-3 d-none" id="editDueDateField">
                                <label for="editReminderDueDate" class="form-label">Ngày đến hạn</label>
                                <input type="date" class="form-control" id="editReminderDueDate"
                                    name="editReminderDueDate">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editReminderDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="editReminderDescription" name="editReminderDescription"
                            rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>