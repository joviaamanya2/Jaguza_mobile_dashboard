<div class="page {{ ($initialPage ?? 'dashboard') === 'sickness' ? 'active' : '' }}" id="page-sickness">
    <div class="section-heading" style="display:flex;align-items:center;justify-content:space-between;gap:16px;">
        <div>
            <h2><i class="fas fa-file-medical" style="color:#dc3545;margin-right:8px;"></i>Sickness Reports</h2>
            <p style="margin:6px 0 0;color:#7b8794;font-size:13px;">Monitor livestock health cases submitted by the field team.</p>
        </div>
    </div>

    <div class="stats-grid" style="margin-bottom:22px;">
        <div class="stat-card"><div class="stat-icon" style="background:#fde8e8;color:#c62828;"><i class="fas fa-exclamation-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['open_reports'] ?? 0) }}</h3><p>Open Cases</p></div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-stethoscope"></i></div><div class="stat-body"><h3>{{ number_format($stats['under_treatment'] ?? 0) }}</h3><p>Under Treatment</p></div></div>
        <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-check-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['resolved_reports'] ?? 0) }}</h3><p>Resolved Cases</p></div></div>
    </div>

    <div class="card" style="padding:0;overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:20px 22px;border-bottom:1px solid #edf0f2;">
            <div><h3 style="margin:0;color:#1f2937;font-size:17px;">Recent health reports</h3><p style="margin:5px 0 0;color:#8c9aab;font-size:12px;">Latest reports received from the backend</p></div>
            <span style="color:#8c9aab;font-size:12px;"><i class="fas fa-clock"></i> Updated {{ now()->format('M j, Y g:i A') }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover" style="margin:0;">
                <thead><tr><th>Report</th><th>Animal</th><th>Symptoms</th><th>Severity</th><th>Status</th><th>Reported by</th><th>Date</th></tr></thead>
                <tbody>
                    @forelse($recentReports ?? [] as $report)
                        @php
                            $status = strtolower($report->status ?? 'open');
                            $severity = strtolower($report->severity_level ?? 'medium');
                            $statusColors = ['open'=>['#fff7ed','#c2410c'],'treating'=>['#eff6ff','#1d4ed8'],'resolved'=>['#f0fdf4','#15803d'],'critical'=>['#fef2f2','#b91c1c'],'referred'=>['#f5f3ff','#6d28d9']];
                            $severityColors = ['mild'=>['#f0fdf4','#15803d'],'medium'=>['#fff7ed','#c2410c'],'severe'=>['#fef2f2','#b91c1c'],'critical'=>['#7f1d1d','#fff']];
                            $statusStyle = $statusColors[$status] ?? ['#f3f4f6','#4b5563'];
                            $severityStyle = $severityColors[$severity] ?? ['#f3f4f6','#4b5563'];
                        @endphp
                        <tr>
                            <td><strong style="color:#374151;">{{ $report->report_id ?? 'SR-' . $report->id }}</strong></td>
                            <td><span style="font-weight:600;">{{ ucfirst($report->affected_animal_type ?? 'Unknown') }}</span><br><small style="color:#8c9aab;">{{ $report->affected_animal_count ?? 0 }} affected</small></td>
                            <td>{{ $report->symptom_primary ?? 'Not specified' }}<br><small style="color:#8c9aab;">{{ $report->symptom_duration ?? 'Duration not recorded' }}</small></td>
                            <td><span class="badge" style="background:{{ $severityStyle[0] }};color:{{ $severityStyle[1] }};">{{ ucfirst($severity) }}</span></td>
                            <td><span class="badge" style="background:{{ $statusStyle[0] }};color:{{ $statusStyle[1] }};">{{ ucfirst($status) }}</span></td>
                            <td>{{ optional($report->user)->name ?? 'System / field team' }}</td>
                            <td style="white-space:nowrap;color:#6b7280;">{{ optional($report->created_at)->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center;padding:55px 20px;color:#8c9aab;"><i class="fas fa-clipboard-check" style="font-size:28px;margin-bottom:10px;display:block;color:#cbd5e1;"></i>No sickness reports have been received yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
