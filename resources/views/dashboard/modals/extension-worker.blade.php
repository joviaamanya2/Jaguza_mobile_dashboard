<!-- ===== EXTENSION WORKER MODAL ===== -->
<div class="modal-overlay" id="extensionWorkerModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="extensionWorkerModalTitle">Add New Extension Worker</h3>
            <button class="modal-close" onclick="closeModal('extensionWorkerModal')">&times;</button>
        </div>
        <form id="extensionWorkerForm" onsubmit="event.preventDefault(); saveExtensionWorker();">
            <input type="hidden" id="extensionWorkerId">
            
            <div class="form-group">
                <label>Full Name <span class="required">*</span></label>
                <input type="text" id="worker_name" class="form-control" placeholder="e.g. John Ssempijja" required>
            </div>
            
            <div class="form-group">
                <label>Email <span class="required">*</span></label>
                <input type="email" id="worker_email" class="form-control" placeholder="john@example.com" required>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Expertise Area <span class="required">*</span></label>
                    <select id="worker_expertise" class="form-control" required>
                        <option value="">Select expertise...</option>
                        <option value="Crop Farming">Crop Farming</option>
                        <option value="Livestock">Livestock</option>
                        <option value="Mixed Farming">Mixed Farming</option>
                        <option value="Aquaculture">Aquaculture</option>
                        <option value="Poultry">Poultry</option>
                        <option value="Agroforestry">Agroforestry</option>
                        <option value="Soil Science">Soil Science</option>
                        <option value="Irrigation & Water Management">Irrigation & Water Management</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Education Level <span class="required">*</span></label>
                    <select id="worker_education" class="form-control" required>
                        <option value="">Select education...</option>
                        <option value="Certificate">Certificate</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Bachelor's Degree">Bachelor's Degree</option>
                        <option value="Master's Degree">Master's Degree</option>
                        <option value="PhD">PhD</option>
                    </select>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Assigned Region <span class="required">*</span></label>
                    <select id="worker_region" class="form-control" required>
                        <option value="">Select region...</option>
                        <option value="Central Region">Central Region</option>
                        <option value="Eastern Region">Eastern Region</option>
                        <option value="Western Region">Western Region</option>
                        <option value="Northern Region">Northern Region</option>
                        <option value="Nile Region">Nile Region</option>
                        <option value="Karamoja Region">Karamoja Region</option>
                        <option value="Bukedi Region">Bukedi Region</option>
                        <option value="Bugisu Region">Bugisu Region</option>
                        <option value="Teso Region">Teso Region</option>
                        <option value="Lango Region">Lango Region</option>
                        <option value="Acholi Region">Acholi Region</option>
                        <option value="West Nile Region">West Nile Region</option>
                        <option value="Kigezi Region">Kigezi Region</option>
                        <option value="Ankole Region">Ankole Region</option>
                        <option value="Bunyoro Region">Bunyoro Region</option>
                        <option value="Toro Region">Toro Region</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Phone Number <span class="required">*</span></label>
                    <input type="text" id="worker_phone" class="form-control" placeholder="+256 700 000 000" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Years of Experience</label>
                    <input type="number" id="worker_experience" class="form-control" placeholder="5" min="0">
                </div>
                <div class="form-group">
                    <label>Languages Spoken</label>
                    <input type="text" id="worker_languages" class="form-control" placeholder="English, Luganda, Swahili">
                </div>
            </div>
            
            <div class="form-group">
                <label>Bio / Notes</label>
                <textarea id="worker_bio" class="form-control" rows="3" placeholder="Brief description about the extension worker..."></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="extensionWorkerSubmitBtn">Save Extension Worker</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('extensionWorkerModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>

