@php
    // Abbreviate a raw number the same way for money and view counts, e.g.
    // 42350000 -> "42.4M", 8600 -> "8.6K". All inputs here come straight from
    // DB sums/counts in DashboardController — nothing on this page is mocked.
    $abbreviate = function ($value) {
        if ($value >= 1000000) return number_format($value / 1000000, 1) . 'M';
        if ($value >= 1000) return number_format($value / 1000, 1) . 'K';
        return number_format($value);
    };
    $marketVolumeDisplay = 'UGX ' . $abbreviate($stats['total_market_volume'] ?? 0);
    $videoViewsDisplay = $abbreviate($stats['total_video_views'] ?? 0);
    $trendClass = fn ($percent) => $percent < 0 ? 'trend-down' : 'trend-up';
    $trendIcon = fn ($percent) => $percent < 0 ? '▼' : '▲';
@endphp
  <!-- ===== ANALYTICS ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'analytics' ? 'active' : '' }}" id="page-analytics">
    <div class="section-heading">
      <h2><i class="fas fa-chart-line" style="color:#2e7d32;margin-right:8px;"></i>Analytics</h2>
      <button class="btn btn-outline" onclick="exportReport()">Export Report</button>
    </div>
    <div class="stats-grid">
      <div class="stat-card"><div class="stat-icon" style="background:#e8f5e9;color:#2e7d32;"><i class="fas fa-users"></i></div><div class="stat-body"><h3>{{ number_format($stats['total_users'] ?? 0) }}</h3><p>Total Users</p><div class="stat-trend {{ $trendClass($userGrowthPercent ?? 0) }}">{{ $trendIcon($userGrowthPercent ?? 0) }} {{ number_format(abs($userGrowthPercent ?? 0), 1) }}% this month</div></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#e3f2fd;color:#0d47a1;"><i class="fas fa-tractor"></i></div><div class="stat-body"><h3>{{ number_format($activeFarms ?? 0) }}</h3><p>Active Farms</p><div class="stat-trend {{ $trendClass($farmGrowthPercent ?? 0) }}">{{ $trendIcon($farmGrowthPercent ?? 0) }} {{ number_format(abs($farmGrowthPercent ?? 0), 1) }}% this month</div></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#fff3e0;color:#e65100;"><i class="fas fa-store"></i></div><div class="stat-body"><h3>{{ $marketVolumeDisplay }}</h3><p>Market Volume</p><div class="stat-trend {{ $trendClass($marketVolumeGrowthPercent ?? 0) }}">{{ $trendIcon($marketVolumeGrowthPercent ?? 0) }} {{ number_format(abs($marketVolumeGrowthPercent ?? 0), 1) }}% this month</div></div></div>
      <div class="stat-card"><div class="stat-icon" style="background:#f3e5f5;color:#4a148c;"><i class="fas fa-play-circle"></i></div><div class="stat-body"><h3>{{ $videoViewsDisplay }}</h3><p>Video Views</p><div class="stat-trend {{ $trendClass($videoViewsGrowthPercent ?? 0) }}">{{ $trendIcon($videoViewsGrowthPercent ?? 0) }} {{ number_format(abs($videoViewsGrowthPercent ?? 0), 1) }}% this month</div></div></div>
    </div>
    <div class="charts-grid">
      <div class="chart-card"><h3>User Growth (12 Months)</h3><canvas id="userGrowthChart"></canvas></div>
      <div class="chart-card"><h3>Reports by Symptom</h3>
        @if(($diseaseLabels ?? collect())->isEmpty())
          <p style="color:#8a97a3;text-align:center;padding:24px 0;">No sickness reports yet.</p>
        @else
          <canvas id="diseaseChart"></canvas>
        @endif
      </div>
    </div>
  </div>
