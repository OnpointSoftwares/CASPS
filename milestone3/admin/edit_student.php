<!-- Edit Student Modal Content -->
<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form>
                <input type="hidden" id="editStudentId" value="<?php echo $studentData['id']; ?>">
                <!-- Add other input fields for editing -->
                <button type="button" class="btn btn-primary" onclick="saveEditedStudent()">Save Changes</button>
            </form>
        </div>
    </div>
</div>
