<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once(__DIR__ . "/../../../functions/auth.php") ;
isLoggedIn();
$ErrorMsg = $_SESSION["error_reminderDueDateEdit"] ?? '';
$oldEditTypeReminder = $_SESSION["oldEditTypeReminder"] ?? "";
unset($_SESSION["error_reminderDueDateEdit"]);
unset($_SESSION["oldEditTypeReminder"]);
?>

<!-- Modal -->
<div class="modal fade" id="editReminderModal" tabindex="-1" aria-labelledby="editReminderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editReminderModalLabel">Chỉnh sửa lời nhắc</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../../handle/reminder_process.php" method="POST" id="editReminderForm">
                    <input type="hidden" name="action" value="edit_reminder">
                    <input type="hidden" name="reminder_id" id="editReminderId">
                    <div class="mb-3">
                        <label for="editReminderType" class="form-label">Loại Nhắc Nhở</label>
                        <select class="form-select" id="editReminderType" name="editReminderType">
                            <option value="budget">Ngân Sách
                            </option>
                            <option value="bill">Hóa Đơn</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryId" class="form-label">Danh Mục Ngân Sách</label>
                        <select class="form-select" id="editCategoryId" name="editCategoryId">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['budget_id']; ?>">
                                    <?= htmlspecialchars($category['name']); ?>
                                    (<?= $category['budget_month'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3" id="inputReminderDate">
                        <label for="editReminderDueDate" class="form-label">Ngày Đến Hạn</label>
                        <input type="date" class="form-control" id="editReminderDueDate" name="editReminderDueDate">
                        <?php if (!empty($ErrorMsg)): ?>
                            <div class="text-danger mt-2">
                                <?= htmlspecialchars($ErrorMsg); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="alert alert-info" role="alert">
                        Khi chi tiêu ngân sách vượt quá 70% sẽ thông báo.
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="editReminderForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var editReminderType = document.getElementById('editReminderType');
        function handleChangeStyle() {
            if (editReminderType.value === 'budget') {
                document.getElementById('inputReminderDate').style.display = 'none';
                document.getElementById('editReminderDueDate').value = null;
            } else {
                document.getElementById('inputReminderDate').style.display = 'block';
            }
        }
        editReminderType.addEventListener('change', handleChangeStyle);
        handleChangeStyle();
        // var editModalInstance = new bootstrap.Modal(document.getElementById('editReminderModal'));
        // editModalInstance.show();
        if (<?= json_encode(!empty($ErrorMsg)); ?>) {
            var editModalInstance = new bootstrap.Modal(document.getElementById('editReminderModal'));
            editModalInstance.show();
        }

        var editModal = document.getElementById('editReminderModal');

        <?php if (!empty($oldEditTypeReminder)): ?>
            editReminderType.value = "<?= $oldEditTypeReminder; ?>";
            handleChangeStyle();
        <?php endif; ?>
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var reminderId = button.getAttribute('data-reminder-id');
            var reminderIdBudget = button.getAttribute('data-reminder-id-budget');
            var reminderDueDate = button.getAttribute('data-reminder-due-date');
            document.getElementById('editReminderId').value = reminderId;
            if (reminderDueDate.length > 0) {
                editReminderType.value = 'bill';
                document.getElementById('editReminderDueDate').value = reminderDueDate;
            }
            document.getElementById('editCategoryId').value = reminderIdBudget;
            handleChangeStyle();
        })
    });
</script>