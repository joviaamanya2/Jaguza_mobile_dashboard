  <!-- ===== ADVERTISEMENTS ===== -->
  <div class="page" id="page-ads">
    <div class="section-heading">
      <h2><i class="fas fa-bullhorn" style="color:#f57c00;margin-right:8px;"></i>Advertisements</h2>
      <button class="btn btn-primary" onclick="openAddAdModal()">+ Create Ad</button>
    </div>
    <div class="ad-grid">
      @forelse($activeAds as $ad)
      <div class="ad-card">
        <div class="ad-banner">📢</div>
        <div class="ad-info">
          <h4>{{ $ad->title }}</h4>
          <p>{{ Str::limit($ad->description ?? '', 80) }}</p>
          <div class="ad-stats">
            <span style="color:#2e7d32;"><i class="fas fa-eye"></i> {{ number_format($ad->views_count ?? 0) }}</span>
            <span style="color:#0d6efd;"><i class="fas fa-mouse-pointer"></i> {{ number_format($ad->clicks_count ?? 0) }}</span>
            <span class="badge {{ $ad->status == 'active' ? 'badge-green' : 'badge-orange' }}">{{ ucfirst($ad->status ?? 'Draft') }}</span>
          </div>
        </div>
      </div>
      @empty
      <div class="ad-card"><div class="ad-info"><h4>No active ads</h4><p>Create your first advertisement</p></div></div>
      @endforelse
    </div>
  </div>
