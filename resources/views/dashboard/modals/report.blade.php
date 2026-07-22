<!-- ===== SICKNESS REPORT MODAL ===== -->
<div class="modal-overlay" id="reportModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="reportModalTitle">New Sickness Report</h3>
            <button class="modal-close" onclick="closeModal('reportModal')">&times;</button>
        </div>
        <form id="reportForm" onsubmit="event.preventDefault(); saveReport();">
            <input type="hidden" id="reportId">

            <div class="form-group">
                <label>Reported By <span class="required">*</span></label>
                <select id="report_user" class="form-control" required>
                    <option value="">Select User</option>
                    @foreach($users ?? [] as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Affected Animal Type <span class="required">*</span></label>
                    <select id="report_animal_type" class="form-control" required>
                        <option value="cattle">Cattle</option>
                        <option value="goat">Goat</option>
                        <option value="sheep">Sheep</option>
                        <option value="pig">Pig</option>
                        <option value="poultry">Poultry</option>
                        <option value="rabbit">Rabbit</option>
                        <option value="fish">Fish</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Affected Count <span class="required">*</span></label>
                    <input type="number" id="report_animal_count" class="form-control" placeholder="1" min="1" value="1" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Primary Symptom <span class="required">*</span></label>
                    <input type="text" id="report_symptom_primary" class="form-control" placeholder="e.g. Fever" required>
                </div>
                <div class="form-group">
                    <label>Duration</label>
                    <select id="report_symptom_duration" class="form-control">
                        <option value="">Select duration</option>
                        <option value="Less than 24 hours">Less than 24 hours</option>
                        <option value="1-2 days">1-2 days</option>
                        <option value="3-5 days">3-5 days</option>
                        <option value="1 week">1 week</option>
                        <option value="2 weeks">2 weeks</option>
                        <option value="More than 2 weeks">More than 2 weeks</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Other Symptoms</label>
                <input type="text" id="report_symptom_other" class="form-control" placeholder="e.g. Loss of appetite, coughing">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Severity Level <span class="required">*</span></label>
                    <select id="report_severity" class="form-control" required>
                        <option value="mild">Mild</option>
                        <option value="medium" selected>Medium</option>
                        <option value="severe">Severe</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="report_status" class="form-control">
                        <option value="open">Open</option>
                        <option value="treating">Under Treatment</option>
                        <option value="resolved">Resolved</option>
                        <option value="critical">Critical</option>
                        <option value="referred">Referred</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Notes</label>
                <textarea id="report_notes" class="form-control" rows="3" placeholder="Additional details..."></textarea>
            </div>

            <div class="form-group">
                <label>Attachments <small style="color:#8c9aab;font-weight:400;text-transform:none;">(one URL per line)</small></label>
                <textarea id="report_attachments" class="form-control" rows="2" placeholder="https://.../photo1.jpg&#10;https://.../video.mp4"></textarea>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="reportSubmitBtn">Save Report</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('reportModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
