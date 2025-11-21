<?php

$oldTypeReminder = $_SESSION['oldTypeReminder'] ?? '';
$oldIdBudgetReminder = $_SESSION['oldIdBudgetReminder'] ?? '';
$oldDateReminder = $_SESSION['oldDateReminder'] ?? '';
require_once(__DIR__ . "/../../../functions/auth.php") ;
isLoggedIn();
unset($_SESSION['oldTypeReminder']);
unset($_SESSION['oldIdBudgetReminder']);
unset($_SESSION['oldDateReminder']);
?>

<!-- Modal -->
<div class="modal fade" id="createReminderModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Tạo Nhắc Nhở Mới</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="../../handle/reminder_process.php" method="POST" id="createReminderForm">
                    <input type="hidden" name="action" value="create_reminder">
                    <div class="mb-3">
                        <label for="reminderType" class="form-label">Loại Nhắc Nhở</label>
                        <select class="form-select" id="reminderType" name="reminderType">
                            <option value="budget" <?= $oldTypeReminder === 'budget' ? 'selected' : '' ?>>Ngân Sách
                            </option>
                            <option value="bill" <?= $oldTypeReminder === 'bill' ? 'selected' : '' ?>>Hóa Đơn</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="budget_id" class="form-label">
                            Danh Mục Ngân Sách
                        </label>
                        <select class="form-select" id="budget_id" name="budget_id">
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['budget_id']; ?>"
                                    <?= $oldIdBudgetReminder == $category['budget_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['name']); ?>
                                    (<?= $category['budget_month'] ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="alert alert-info" role="alert">
                        Khi chi tiêu ngân sách vượt quá 70% sẽ thông báo.
                    </div>
                    <div class="mb-3">
                        <label for="reminderDueDate" class="form-label">Ngày Đến Hạn (Cho Hóa Đơn)</label>
                        <input type="date" class="form-control" id="reminderDueDate" name="reminderDueDate"
                            value="<?= htmlspecialchars($oldDateReminder); ?>">
                        <?php if (isset($_SESSION['error_reminderDueDate'])): ?>
                            <div class="text-danger">
                                <?= $_SESSION['error_reminderDueDate']; ?>
                            </div>
                            <?php unset($_SESSION['error_reminderDueDate']); ?>
                        <?php endif; ?>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="createReminderForm" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function handleStyleChange() {
            if (document.getElementById('reminderType').value === 'bill') {
                document.getElementById('reminderDueDate').parentElement.style.display = 'block';
            } else {
                document.getElementById('reminderDueDate').parentElement.style.display = 'none';
            }
        }
        document.getElementById('reminderType').addEventListener('change', handleStyleChange)
        handleStyleChange();

        <?php if (!empty($oldTypeReminder) || !empty($oldIdBudgetReminder) || !empty($oldDateReminder)): ?>
            var createReminderModal = new bootstrap.Modal(document.getElementById('createReminderModal'));
            createReminderModal.show();
        <?php endif; ?>
    });
</script>