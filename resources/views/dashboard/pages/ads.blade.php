  <!-- ===== ADVERTISEMENTS ===== -->
  <div class="page {{ ($initialPage ?? 'dashboard') === 'ads' ? 'active' : '' }}" id="page-ads">
    <div class="section-heading">
      <h2><i class="fas fa-bullhorn" style="color:#f57c00;margin-right:8px;"></i>Advertisements</h2>
      <button class="btn btn-primary" onclick="openAddAdModal()">+ Create Ad</button>
    </div>
    <div class="ad-grid">
      @forelse($activeAds as $ad)
      <div class="ad-card">
        @if($ad->image_url)
        <div class="ad-banner" style="padding:0;overflow:hidden;"><img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" style="width:100%;height:100%;object-fit:cover;"></div>
        @else
        <div class="ad-banner">📢</div>
        @endif
        <div class="ad-info">
          <h4>{{ $ad->title }}</h4>
          <p>{{ Str::limit($ad->description ?? '', 80) }}</p>
          <p style="color:#6a7a8a;font-size:11px;">Submitted by {{ $ad->creator->name ?? 'Unknown' }}</p>
          <div class="ad-stats">
            <span style="color:#2e7d32;"><i class="fas fa-eye"></i> {{ number_format($ad->views_count ?? 0) }}</span>
            <span style="color:#0d6efd;"><i class="fas fa-mouse-pointer"></i> {{ number_format($ad->clicks_count ?? 0) }}</span>
            <span class="badge {{ $ad->status == 'active' ? 'badge-green' : ($ad->status == 'pending' ? 'badge-orange' : 'badge-red') }}">{{ ucfirst($ad->status ?? 'Draft') }}</span>
          </div>
          <div style="margin-top:8px;display:flex;gap:8px;">
            @if($ad->status === 'pending')
            <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--green-600);" onclick="approveAd({{ $ad->id }})">
              <i class="fas fa-check"></i> Approve
            </button>
            @endif
            <button class="btn btn-outline" style="padding:4px 10px;font-size:11px;color:var(--red);" onclick="deleteAd({{ $ad->id }})">
              <i class="fas fa-trash"></i> Delete
            </button>
          </div>
        </div>
      </div>
      @empty
      <div class="ad-card"><div class="ad-info"><h4>No ads yet</h4><p>Create your first advertisement, or wait for submissions from the app</p></div></div>
      @endforelse
    </div>
  </div>
