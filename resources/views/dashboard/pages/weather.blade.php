  <!-- ===== WEATHER ===== -->
  <div class="page" id="page-weather">
    <div class="section-heading">
      <h2><i class="fas fa-cloud-sun" style="color:#f57c00;margin-right:8px;"></i>Weather Updates</h2>
      <button class="btn btn-outline" onclick="refreshWeather()"><i class="fas fa-sync"></i> Refresh</button>
    </div>
    <div class="stats-grid">
      @forelse($weatherUpdates as $weather)
      <div class="stat-card">
        <div class="stat-icon" style="font-size:28px;background:{{ $weather->condition == 'Sunny' ? '#fff3e0' : ($weather->condition == 'Rain' ? '#e3f2fd' : '#f0f2f5') }};">
          @if($weather->condition == 'Sunny') &#x2600;&#xFE0F;
          @elseif($weather->condition == 'Rain') &#x1F327;&#xFE0F;
          @elseif($weather->condition == 'Cloudy') &#x26C5;
          @elseif($weather->condition == 'Hot') &#x1F324;&#xFE0F;
          @else &#x1F31E;
          @endif
        </div>
        <div class="stat-body">
          <h3>{{ number_format($weather->temperature ?? 0) }}&deg;C</h3>
          <p>{{ $weather->location ?? 'Unknown' }} – {{ $weather->condition ?? 'N/A' }}</p>
        </div>
      </div>
      @empty
      <div class="stat-card"><div class="stat-icon" style="font-size:28px;background:#f0f2f5;">&#x1F31E;</div><div class="stat-body"><h3>N/A</h3><p>No weather data</p></div></div>
      @endforelse
    </div>
    <div class="card" style="margin-top:8px;">
      <h3 style="font-size:14px;font-weight:600;margin-bottom:16px;color:#1a1a2e;">Farming Advisories</h3>
      @forelse($weatherAdvisories ?? [] as $advisory)
      <div class="notif-item">
        <div class="notif-dot" style="background:{{ $advisory->color ?? '#fd7e14' }};"></div>
        <div class="notif-body">
          <p><strong style="color:#1a1a2e;">{{ $advisory->location ?? 'N/A' }}:</strong> {{ $advisory->message ?? 'No advisory' }}</p>
          <span>Valid until {{ $advisory->valid_until ? $advisory->valid_until->format('M d, Y') : 'N/A' }}</span>
        </div>
      </div>
      @empty
      <div style="text-align:center;padding:20px;color:#6a7a8a;"><p>No weather advisories available</p></div>
      @endforelse
    </div>
  </div>
