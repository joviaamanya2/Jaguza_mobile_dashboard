<!-- ===== DOCTOR MODAL ===== -->
<div class="modal-overlay" id="doctorModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="doctorModalTitle">Add New Doctor</h3>
            <button class="modal-close" onclick="closeModal('doctorModal')">&times;</button>
        </div>
        <form id="doctorForm" onsubmit="event.preventDefault(); saveDoctor();">
            <input type="hidden" id="doctorId">
            
            <div class="form-group">
                <label>Doctor Name <span class="required">*</span></label>
                <input type="text" id="doctor_name" class="form-control" placeholder="Dr. John Doe" required>
            </div>
            
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" id="doctor_email" class="form-control" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Specialization <span class="required">*</span></label>
                    <input type="text" id="doctor_specialization" class="form-control" placeholder="Bovine Medicine" required>
                </div>
                <div class="form-group">
                    <label>License Number <span class="required">*</span></label>
                    <input type="text" id="doctor_license" class="form-control" placeholder="LIC-1234" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Location <span class="required">*</span></label>
                    <input type="text" id="doctor_location" class="form-control" placeholder="Kampala, Uganda" required>
                </div>
                <div class="form-group">
                    <label>Phone <span class="required">*</span></label>
                    <input type="text" id="doctor_phone" class="form-control" placeholder="+256 700 000 000" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Years of Experience</label>
                    <input type="number" id="doctor_experience" class="form-control" placeholder="5" min="0">
                </div>
                <div class="form-group">
                    <label>Consultation Fee</label>
                    <input type="number" id="doctor_fee" class="form-control" placeholder="50000" min="0">
                </div>
            </div>
            
            <div class="form-group">
                <label>Bio</label>
                <textarea id="doctor_bio" class="form-control" rows="3" placeholder="Brief description about the doctor..."></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="doctorSubmitBtn">Save Doctor</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('doctorModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
