  <!-- ===== ANALYTICS ===== -->
  <div class="page" id="page-analytics">
    <div class="section-heading">
      <h2><i class="fas fa-chart-line" style="color:#2e7d32;margin-right:8px;"></i>Analytics</h2>
      <button class="btn btn-outline" onclick="exportReport()">Export Report</button>
    </div>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-users"></i></div><div class="stat-body"><h3>{{ number_format($stats['total_users'] ?? 0) }}</h3><p>Total Users</p><div class="stat-trend trend-up">{{ number_format($userGrowthPercent ?? 0, 1) }}% growth</div></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-eye"></i></div><div class="stat-body"><h3>148K</h3><p>Monthly App Opens</p><div class="stat-trend trend-up">22.1% growth</div></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-store"></i></div><div class="stat-body"><h3>UGX 42M</h3><p>Market Volume</p><div class="stat-trend trend-up">18.3% growth</div></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#f3e5f5;color:#4a148c;"><i class="fas fa-play-circle"></i></div><div class="stat-body"><h3>{{ number_format($stats['total_videos'] ?? 0) }}</h3><p>Video Views</p><div class="stat-trend trend-up">31.2% growth</div></div></div>
    </div>
    <div class="charts-grid">
      <div class="chart-card"><h3>User Growth (12 Months)</h3><canvas id="userGrowthChart"></canvas></div>
      <div class="chart-card"><h3>Disease Cases by Type</h3><canvas id="diseaseChart"></canvas></div>
    </div>
  </div>
