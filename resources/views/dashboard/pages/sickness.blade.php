  <!-- ===== SICKNESS REPORTS - SUBMIT FORM ===== -->
  <div class="page" id="page-sickness">
    <div class="section-heading">
      <h2><i class="fas fa-file-medical" style="color:#dc3545;margin-right:8px;"></i>Submit Sickness Report</h2>
    </div>

    <!-- Stats Summary -->
    <div class="stats-grid" style="margin-bottom:20px;">
      <div class="stat-card"><div class="stat-icon" style="background:#fde8e8;color:#c62828;"><i class="fas fa-exclamation-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['open_reports'] ?? 0) }}</h3><p>Open Cases</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-clock"></i></div><div class="stat-body"><h3>{{ number_format($stats['under_treatment'] ?? 0) }}</h3><p>Under Treatment</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['resolved_reports'] ?? 0) }}</h3><p>Resolved</p></div></div>
    </div>

    <!-- Inline Submission Form -->
    <div class="card" style="max-width:800px;margin:0 auto;">
      <h3 style="font-size:16px;font-weight:600;margin-bottom:20px;color:#1a1a2e;">
        <i class="fas fa-pen" style="color:#dc3545;margin-right:8px;"></i> Report Livestock Sickness
      </h3>
      <form id="inlineReportForm" onsubmit="event.preventDefault(); saveInlineReport();">
        <input type="hidden" id="inlineReportId">
        
        <div class="form-row">
          <div class="form-group">
            <label>Affected Animal Type <span class="required">*</span></label>
            <select id="inline_animal_type" class="form-control" required>
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
            <input type="number" id="inline_animal_count" class="form-control" placeholder="e.g. 1" min="1" value="1" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Primary Symptom <span class="required">*</span></label>
            <input type="text" id="inline_symptom_primary" class="form-control" placeholder="e.g. Fever, Diarrhoea, Limping" required>
          </div>
          <div class="form-group">
            <label>Duration</label>
            <select id="inline_symptom_duration" class="form-control">
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
          <input type="text" id="inline_symptom_other" class="form-control" placeholder="e.g. Loss of appetite, coughing, nasal discharge">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label>Severity Level <span class="required">*</span></label>
            <select id="inline_severity" class="form-control" required>
              <option value="mild">Mild</option>
              <option value="medium" selected>Medium</option>
              <option value="severe">Severe</option>
              <option value="critical">Critical</option>
            </select>
          </div>
          <div class="form-group">
            <label>Reported By</label>
            <select id="inline_user" class="form-control">
              <option value="">Select User (optional)</option>
              @foreach($users ?? [] as $u)
              <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Additional Notes</label>
          <textarea id="inline_notes" class="form-control" rows="3" placeholder="Any other relevant details about the sickness..."></textarea>
        </div>

        <div class="modal-footer" style="border:none;padding:0;margin-top:20px;">
          <button type="submit" class="btn btn-primary" id="inlineReportSubmitBtn" style="flex:0 1 auto;min-width:200px;">
            <i class="fas fa-paper-plane"></i> Submit Report
          </button>
          <button type="reset" class="btn btn-outline" onclick="resetInlineReportForm()">Clear Form</button>
        </div>
      </form>
    </div>

    <div style="text-align:center;margin-top:16px;font-size:12px;color:#8c9aab;">
      <i class="fas fa-info-circle"></i> Submitted reports will appear in the <a href="#" onclick="navigate('dashboard','Dashboard Overview')" style="color:#2e7d32;text-decoration:underline;">Dashboard Overview</a>.
    </div>
  </div>
