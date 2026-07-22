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
          <thead><tr><th>#</th><th>Animal</th><th>Farm</th><th>Symptoms</th><th>Reported By</th><th>Date</th><th>Status</th><th>Doctor</th></tr></thead>
          <tbody>
            @forelse($recentReports as $report)
            <tr>
              <td>{{ $report->report_id ?? '#' }}</td>
              <td>{{ $report->animal->name ?? $report->animal->identification_number ?? 'N/A' }}</td>
              <td>{{ $report->animal->farm->name ?? 'N/A' }}</td>
              <td>{{ Str::limit($report->symptoms ?? '', 30) }}</td>
              <td>{{ $report->reporter->name ?? 'Unknown' }}</td>
              <td>{{ $report->created_at ? $report->created_at->format('M d, Y') : 'N/A' }}</td>
              <td><span class="badge {{ getStatusBadge($report->status ?? '') }}">{{ getStatusDisplay($report->status ?? '') }}</span></td>
              <td>{{ $report->doctor->name ?? ($report->doctor->user->name ?? 'Unassigned') }}</td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center;padding:40px;color:#6a7a8a;">No sickness reports found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
