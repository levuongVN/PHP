<div class="modal fade" id="addReminderModal" tabindex="-1" aria-labelledby="addReminderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReminderModalLabel">
                    <i class="fas fa-plus me-2"></i>Thêm nhắc nhở mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addReminderForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reminderType" class="form-label">Loại nhắc nhở</label>
                                <select class="form-select" id="reminderType" name="reminderType">
                                    <option value="budget">Cảnh báo ngân sách</option>
                                    <option value="bill">Hóa đơn</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reminderName" class="form-label">Tên nhắc nhở</label>
                                <input type="text" class="form-control" id="reminderName" name="reminderName"
                                    placeholder="Nhập tên nhắc nhở">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="reminderAmount" class="form-label">Số tiền</label>
                                <input type="number" class="form-control" id="reminderAmount" name="reminderAmount"
                                    placeholder="Nhập số tiền">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3" id="percentageField">
                                <label for="reminderPercentage" class="form-label">Phần trăm cảnh báo (%)</label>
                                <input type="number" class="form-control" id="reminderPercentage"
                                    name="reminderPercentage" min="0" max="200" placeholder="Nhập phần trăm">
                            </div>
                            <div class="mb-3 d-none" id="dueDateField">
                                <label for="reminderDueDate" class="form-label">Ngày đến hạn</label>
                                <input type="date" class="form-control" id="reminderDueDate" name="reminderDueDate">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="reminderDescription" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="reminderDescription" name="reminderDescription" rows="3"
                            placeholder="Nhập mô tả chi tiết"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary">Thêm nhắc nhở</button>
            </div>
        </div>
    </div>
</div>
