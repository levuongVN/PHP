<?php
require_once(__DIR__ . "/../../../functions/auth.php") ;
isLoggedIn();
?>
<!-- Delete Reminder Modal -->
<div class="modal fade" id="deleteReminderModal" tabindex="-1" aria-labelledby="deleteReminderModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-warning">
      
      <!-- Header -->
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="deleteReminderModalLabel">
          <i class="fas fa-exclamation-triangle me-2"></i> Xóa lời nhắc
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Body -->
      <div class="modal-body text-center">
        <p class="fs-6">Bạn có chắc chắn muốn xóa lời nhắc này?</p>
        <p class="text-muted small">Hành động này không thể hoàn tác.</p>
      </div>
      
      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
        <form id="deleteReminderForm" action="../../handle/reminder_process.php" method="POST">
          <input type="hidden" name="action" value="delete_reminder">
          <input type="hidden" name="reminderId" id="deleteReminderId" value="">
          <button type="submit" class="btn btn-warning text-dark">
            <i class="fas fa-trash-alt me-1"></i> Xóa
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
  // Khi mở modal, set reminderId
  var deleteModal = document.getElementById('deleteReminderModal');
  deleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // nút gọi modal
    var reminderId = button.getAttribute('data-reminder-id');
    document.getElementById('deleteReminderId').value = reminderId;
  });
</script>
