  <!-- ===== SICKNESS REPORTS ===== -->
  <div class="page" id="page-sickness">
    <div class="section-heading">
      <h2><i class="fas fa-file-medical" style="color:#dc3545;margin-right:8px;"></i>Sickness Reports</h2>
      <button class="btn btn-primary" onclick="openAddReportModal()">+ New Report</button>
    </div>
    <div class="stats-grid" style="margin-bottom:20px;">
      <div class="stat-card"><div class="stat-icon" style="background:#fde8e8;color:#c62828;"><i class="fas fa-exclamation-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['open_reports'] ?? 0) }}</h3><p>Open Cases</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-clock"></i></div><div class="stat-body"><h3>{{ number_format($stats['under_treatment'] ?? 0) }}</h3><p>Under Treatment</p></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['resolved_reports'] ?? 0) }}</h3><p>Resolved</p></div></div>
    </div>
    <div class="card">
      <div class="table-wrap">
        <table>
          <thead><tr><th>#</th><th>Animal Type</th><th>Count</th><th>Reported By</th><th>Primary Symptom</th><th>Other Symptoms</th><th>Duration</th><th>Severity</th><th>Attachments</th><th>Date</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            @forelse($recentReports as $report)
            @php
              $severity = strtolower($report->severity_level ?? 'medium');
              $severityBadge = match($severity) {
                  'critical', 'severe' => 'badge-red',
                  'medium' => 'badge-orange',
                  'mild' => 'badge-green',
                  default => 'badge-gray',
              };
              $attachmentCount = is_array($report->attachments ?? null) ? count($report->attachments) : 0;
            @endphp
            <tr>
              <td>{{ $report->report_id ?? '#' }}</td>
              <td>{{ ucfirst($report->affected_animal_type ?? 'N/A') }}</td>
              <td><span class="badge badge-purple">{{ number_format($report->affected_animal_count ?? 0) }}</span></td>
              <td>{{ $report->user->name ?? 'Unknown' }}</td>
              <td>{{ $report->symptom_primary ?? 'N/A' }}</td>
              <td>{{ Str::limit($report->symptom_other ?? '—', 30) }}</td>
              <td>{{ $report->symptom_duration ?? 'N/A' }}</td>
              <td><span class="badge {{ $severityBadge }}">{{ ucfirst($severity) }}</span></td>
              <td>
                @if($attachmentCount > 0)
                  <span class="badge badge-blue"><i class="fas fa-paperclip"></i> {{ $attachmentCount }}</span>
                @else
                  <span style="color:#b0bec5;">—</span>
                @endif
              </td>
              <td>{{ $report->created_at ? $report->created_at->format('M d, Y') : 'N/A' }}</td>
              <td><span class="badge {{ getStatusBadge($report->status ?? '') }}">{{ getStatusDisplay($report->status ?? '') }}</span></td>
              <td>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;" onclick="editReport({{ $report->id }})"><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="deleteReport({{ $report->id }})"><i class="fas fa-trash"></i></button>
              </td>
            </tr>
            @empty
            <tr><td colspan="12" style="text-align:center;padding:40px;color:#6a7a8a;">No sickness reports found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
