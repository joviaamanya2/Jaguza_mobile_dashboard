<!-- ===== REPORT MODAL ===== -->
<div class="modal-overlay" id="reportModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="reportModalTitle">Add New Report</h3>
            <button class="modal-close" onclick="closeModal('reportModal')">&times;</button>
        </div>
        <form id="reportForm" onsubmit="event.preventDefault(); saveReport();">
            <input type="hidden" id="reportId">
            
            <div class="form-group">
                <label>Animal <span class="required">*</span></label>
                <select id="report_animal" class="form-control" required>
                    <option value="">Select Animal</option>
                    @foreach($animals ?? [] as $animal)
                    <option value="{{ $animal->id }}">{{ $animal->name ?? $animal->identification_number }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Symptoms <span class="required">*</span></label>
                <textarea id="report_symptoms" class="form-control" rows="3" placeholder="Describe the symptoms..." required></textarea>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Status <span class="required">*</span></label>
                    <select id="report_status" class="form-control" required>
                        <option value="open">Open</option>
                        <option value="treating">Under Treatment</option>
                        <option value="resolved">Resolved</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Doctor</label>
                    <select id="report_doctor" class="form-control">
                        <option value="">Unassigned</option>
                        @foreach($doctors ?? [] as $doctor)
                        <option value="{{ $doctor->id }}">Dr. {{ $doctor->user->name ?? '' }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label>Diagnosis</label>
                <textarea id="report_diagnosis" class="form-control" rows="2" placeholder="Initial diagnosis..."></textarea>
            </div>
            
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Report</button>
                <button type="button" class="btn btn-outline" onclick="closeModal('reportModal')">Cancel</button>
            </div>
        </form>
    </div>
</div>
